<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Statamic\Auth\User;
use Statamic\Contracts\Assets\Asset;
use Statamic\Contracts\Entries\Entry;
use Statamic\Contracts\Taxonomies\Term;
use Statamic\Fields\Field;
use Statamic\Fields\Value;
use Statamic\Fields\LabeledValue;

class OrdersCollectionExport implements FromCollection, WithHeadings
{
    use Exportable;

    private Collection $collection;

    /**
     * Set the collection of entries that is about to be exported
     *
     * @param Collection $items
     * @return self
     */
    public function setItems(Collection $items): OrdersCollectionExport
    {
        $notAllEntries = $items->some(fn($entry) => !($entry instanceof Entry));

        if ($notAllEntries) {
            throw new \InvalidArgumentException('Collection export expects a collection of entries.');
        }

        $this->collection = $items;

        return $this;
    }

    /**
     * Get the fields that should be included in the export for this collection
     *
     * @return Collection
     */
    public function fields(): Collection
    {
        /** @var \Statamic\Entries\Entry $entry */
        $entry = $this->collection->first();

        return $entry->blueprint()
            ->sections()
            ->flatMap(fn($section) => $section->fields()->all())
            ->filter(fn(Field $field) => $this->shouldFieldBeIncluded($field));
    }

    /**
     * Get the headings for the export
     *
     * @return array
     */
    public function headings(): array
    {
      $headings = $this->fields()->map->display()->all();
      if ($this->collection->first()->collection()->title() === "Orders") {
        array_unshift($headings, "Length");
      }
      return $headings;
    }

    /**
     * Transform the collection of entries into a collection of export rows.
     *
     * @return Collection
     */
    public function collection(): Collection
    {

        // Get all heading handles.
        $headings = $this->fields()->keys();

        if ($this->collection->first()->collection()->title() === "Orders") {
          $headings->prepend("length");
          $individualEntries = $this->collection->filter(function (\Statamic\Entries\Entry $entry) {
            return !$entry->competitors;
          })->map(function (\Statamic\Entries\Entry $entry) use ($headings) {
              // Map every heading to the corresponding value for this entry.
              return $headings->map(function ($heading) use ($entry) {
                  if($heading == "length") {
                    $value = $entry["items"][0]["variant"]["variant"];
                  } else {
                    $value = $entry->augmentedValue($heading);
                  }
                  return $this->toString($value);
              });
            });

          $groupEntries = $this->collection->filter(function (\Statamic\Entries\Entry $entry) {
              return !!$entry->competitors;
            })->map(function (\Statamic\Entries\Entry $entry) use ($headings) {
              return collect($entry->competitors)->skip(1)->map(function ($competitor) use ($entry, $headings) {
                return $headings->map(function ($heading) use ($entry, $competitor) {
                  switch($heading) {
                    case "customer":
                      $value = $competitor["cells"][0] . " " . $competitor["cells"][1];
                      break;
                    case "shirt_size":
                      $value = $competitor["cells"][2];
                      break;
                    case "phone":
                      $value = $competitor["cells"][3];
                      break;
                    case "birthyear":
                      $value = $competitor["cells"][4];
                      break;
                    case "gender":
                      $value = $competitor["cells"][5];
                      break;
                    case "best_time":
                      $value = $competitor["cells"][6];
                      break;
                    case "dp_vet":
                      $value = $competitor["cells"][7] === "1" ? "TRUE" : "";
                      break;
                    case "length":
                      $value = $competitor["cells"][8];
                      break;
                    default:
                      $value = $entry->augmentedValue($heading);
                  }
                  return $this->toString($value);
                });
              });
            });
          return $individualEntries->merge($groupEntries);
        } else {
          // Transform every entry into an array with the entry values.
          return $this->collection->map(function (\Statamic\Entries\Entry $entry) use ($headings) {
            // Map every heading to the corresponding value for this entry.
            return $headings->map(function ($heading) use ($entry) {
                $value = $entry->augmentedValue($heading);
                return $this->toString($value);
            });
          });
        }
    }

    /**
     * Convert an augmented entry field value to a string so it can be put in the Excel column
     *
     * @param $value
     * @return bool|float|int|string|null
     */
    function toString($value)
    {
        // It's an augmented value, unpack it so we can use it.
        if ($value instanceof Value) {
            $value = $value->value();
        }


        if ($value instanceof Carbon) {
            return $value->format('d-m-Y H:i');
        }

        if ($value instanceof Entry) {
            return $value->get('title');
        }

        if ($value instanceof User) {
            return $value->name();
        }

        if ($value instanceof Term) {
            return $value->title();
        }

        if ($value instanceof LabeledValue) {
            return $value->label();
        }

        if ($value instanceof Asset) {
            return $value->url();
        }

        if (is_null($value) || is_scalar($value)) {
            return $value;
        }

        if (is_array($value)) {
            return json_encode($value);
        }

        Log::warning('[EntryCollectionExport] unhandled value', ['value' => $value, 'type' => gettype($value)]);

        return $value;
    }

    /**
     * Whether this specific field should be included in the export
     *
     * @param Field $field
     * @return bool
     */
    protected function shouldFieldBeIncluded(Field $field): bool
    {
        return !in_array($field->type(), config('entries-export.excluded_field_types'));
    }

    /**
     * Get the filename of the export
     *
     * @return string
     */
    public function getFileName(): string
    {
        if ($this->collection->first()->collection()->title() === "Orders") {
          return "Lista_prijavljenih.xlsx";
        } else {
          $entry = $this->collection->first();
          $format = config('entries-export.export_format');
          return sprintf(
              '%s export %s.%s',
              $entry->collection()->title(),
              Carbon::now()->toDateTimeString(),
              $format,
          );
        }
    }
}
