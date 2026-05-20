@extends('themes.neon-pulse.layouts.base')

@section('content')
<div className="min-h-screen bg-[#050505] text-white py-24 px-4">
    <div className="max-w-4xl mx-auto">
        <header className="text-center mb-16">
            <h1 className="text-5xl font-black tracking-tighter mb-4">SPECIFIED COMMERCIAL TRANSACTIONS</h1>
            <p className="text-slate-500 uppercase tracking-[0.3em] text-xs font-bold">Legal Information & Compliance</p>
        </header>

        <div className="glass p-12 rounded-[3.5rem] border border-white/5 bg-white/5 relative overflow-hidden">
            <div className="absolute top-0 right-0 w-96 h-96 bg-emerald-500/5 blur-[120px] pointer-events-none"></div>
            
            <div className="space-y-12">
                <section>
                    <h3 className="text-xs font-black text-slate-500 uppercase tracking-[0.2em] mb-4">Seller Information</h3>
                    <table className="w-full text-left">
                        <tr className="border-b border-white/5">
                            <th className="py-4 text-sm font-bold text-slate-400 w-1/3">Shop Name</th>
                            <td className="py-4 font-black">{{ $setting->shop_name }}</td>
                        </tr>
                        <tr className="border-b border-white/5">
                            <th className="py-4 text-sm font-bold text-slate-400">Representative</th>
                            <td className="py-4 font-bold">{{ $setting->trade_law_manager }}</td>
                        </tr>
                        <tr className="border-b border-white/5">
                            <th className="py-4 text-sm font-bold text-slate-400">Address</th>
                            <td className="py-4 text-slate-300 font-bold">{{ $setting->trade_law_address }}</td>
                        </tr>
                        <tr>
                            <th className="py-4 text-sm font-bold text-slate-400">Contact</th>
                            <td className="py-4">
                                <p className="font-black text-emerald-400">{{ $setting->trade_law_tel }}</p>
                                <p className="text-xs text-slate-500 font-bold">{{ $setting->trade_law_email }}</p>
                            </td>
                        </tr>
                    </table>
                </section>

                <section>
                    <h3 className="text-xs font-black text-slate-500 uppercase tracking-[0.2em] mb-4">Service Details</h3>
                    <div className="grid gap-8">
                        <div>
                            <h4 className="text-sm font-black text-white mb-2 underline decoration-emerald-500 decoration-2 underline-offset-4">Pricing</h4>
                            <p className="text-slate-400 text-sm leading-relaxed whitespace-pre-wrap">{{ $setting->trade_law_price_info }}</p>
                        </div>
                        <div>
                            <h4 className="text-sm font-black text-white mb-2 underline decoration-emerald-500 decoration-2 underline-offset-4">Payment Methods</h4>
                            <p className="text-slate-400 text-sm leading-relaxed whitespace-pre-wrap">{{ $setting->trade_law_payment_methods }}</p>
                        </div>
                        <div>
                            <h4 className="text-sm font-black text-white mb-2 underline decoration-emerald-500 decoration-2 underline-offset-4">Delivery</h4>
                            <p className="text-slate-400 text-sm leading-relaxed whitespace-pre-wrap">{{ $setting->trade_law_delivery_info }}</p>
                        </div>
                        <div>
                            <h4 className="text-sm font-black text-white mb-2 underline decoration-emerald-500 decoration-2 underline-offset-4">Return Policy</h4>
                            <p className="text-slate-400 text-sm leading-relaxed whitespace-pre-wrap">{{ $setting->trade_law_return_policy }}</p>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>
@endsection
