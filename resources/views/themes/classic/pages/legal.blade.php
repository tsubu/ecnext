@extends('themes.classic.pages.layout')

@section('content')
<div class="mb-16 text-center">
    <span class="text-[10px] font-black uppercase tracking-[0.4em] text-amber-600 mb-4 block">
        {{ __('Official Legal Disclosure') }}
    </span>
    <h1 class="page-title text-4xl md:text-5xl text-slate-900">{{ $page->title }}</h1>
</div>

<div class="glass rounded-[2.5rem] overflow-hidden border border-black/[0.03] shadow-sm">
    <table class="w-full text-left border-collapse">
        <tbody class="divide-y divide-black/[0.03]">
            @php
                $legalLabels = [
                    'seller_name' => '販売業者',
                    'representative' => '運営責任者',
                    'address' => '所在地',
                    'phone' => '電話番号',
                    'email' => 'メールアドレス',
                    'fees' => '商品代金以外の必要料金',
                    'payment_methods' => '支払方法',
                    'payment_deadline' => '支払期限',
                    'delivery_time' => '引渡し時期',
                    'returns' => '返品・交換について'
                ];
            @endphp
            @foreach($legalLabels as $key => $label)
                @if(!empty($page->legal_data[$key]))
                    <tr class="group hover:bg-black/[0.01] transition-colors">
                        <th class="w-1/3 px-8 py-8 text-xs font-black uppercase tracking-widest text-slate-400 bg-black/[0.01]">
                            {{ $label }}
                        </th>
                        <td class="px-8 py-8 text-sm font-medium text-slate-700 leading-relaxed">
                            {!! nl2br(e($page->legal_data[$key])) !!}
                        </td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
</div>

<div class="mt-12 p-8 bg-amber-50 rounded-3xl border border-amber-100/50">
    <p class="text-xs text-amber-700 leading-loose">
        ※上記以外の事項に関しましては、お取引の際に請求があれば遅延なく提示いたします。
    </p>
</div>
@endsection
