@component('mail::message')
# Potrditev prijave

Spoštovani,
potrjujemo, da je bila vaša prijava (**#{{ $order->orderNumber() }}**) zabeležena kot plačana. Pregled naročila je objavljen spodaj, prav tako pa je priložen tudi račun.

@component('mail::table')
| Prijava       | Količina         | Cena |
| :--------- | :------------- | :----- |
@foreach ($order->lineItems() as $lineItem)
@php
$site = \Statamic\Facades\Site::current();
@endphp
| [{{ $lineItem->product()->get('title') }} - {{ $lineItem->variant()["variant"] }}]({{ optional($lineItem->product()->resource())->absoluteUrl() }}) | {{ $lineItem->quantity() }} | {{ \DoubleThreeDigital\SimpleCommerce\Currency::parse($lineItem->total(), $site) }} |
@endforeach
@endcomponent

## Tekmovalec oz. nosilec prijave

@if($order->customer())
* **Ime in priimek:** {{ $order->customer()->get("first_name") }} {{ $order->customer()->get("last_name") }}
* **Email:** {{ $order->customer()->email() }}
@if($order->get('address'))
* **Naslov:** {{ $order->get("address") }}, {{ $order->get("zip") }} {{ $order->get("city") }}, {{ $order->get("country") }}
@endif
@if($order->get('gender'))
* **Spol:** {{ __('checkout.' . $order->get('gender')) }}
@endif
@if($order->get('birthyear'))
* **Letnica rojstva:** {{ $order->get("birthyear") }}
@endif
* **Tel. številka:** {{ $order->get("phone") }}
@if($order->get('club'))
* **Klub:** {{ $order->get("club") }}
@endif
@if($order->get('best_time'))
* **Čas:** {{ $order->get("best_time") }}
@endif
@if($order->get('shirt_size'))
* **Vel. majice:** {{ $order->get("shirt_size") }}
@endif
@if($order->get('primary_school'))
* **Osnovna šola:** {{ $order->get("primary_school") }}
@endif
@endif

<br>

@if($lineItem->product()->get('title') === 'Canicross')
## Podatki o psu

* **Ime:** {{ $order->get("dog_name") }}
* **Pasma:** {{ $order->get("dog_breed") }}
* **Leto rojstva:** {{ $order->get("dog_birthyear") }}
@endif

<br>

Če imate kakšno vprašanje o prijavi, prosimo, stopite v kontakt.

Hvala,<br>
{{ config('app.name') }}
@endcomponent
