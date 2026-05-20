import { Head, useForm, router } from '@inertiajs/react';
import { useTranslation } from '@/Helpers/translations';

export default function Checkout({ auth, cart, paymentMethods }) {
    const { __ } = useTranslation();
    const { items, subtotal, discount_amount, discount_label, total, coupon, points_used, points_discount } = cart;
    const [pointsInput, setPointsInput] = React.useState(points_used || 0);

    const applyPoints = () => {
        router.post(route('cart.points.apply'), { amount: pointsInput }, {
            preserveScroll: true,
        });
    };
    const { data, setData, post, processing, errors } = useForm({
        payment_method: '',
        shipping_address: {
            name: '',
            street: '',
            city: '',
            postal_code: '',
        }
    });

    // Simple tax calculation (10% of subtotal)
    const tax = Math.round(subtotal * 0.1);

    const submit = (e) => {
        e.preventDefault();
        post(route('checkout.store'));
    };

    return (
        <div className="bg-slate-50 min-h-screen font-['Inter']">
            <Head title={`${__('Secure Checkout')} | EC-NEXT`} />
            
            <div className="max-w-7xl mx-auto px-6 py-16">
                <div className="flex items-center gap-4 mb-12">
                    <div className="w-10 h-10 bg-indigo-600 rounded-full flex items-center justify-center text-white font-black shadow-lg shadow-indigo-600/30">
                        <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <h1 className="text-4xl font-black text-slate-900 tracking-tighter">{__('Secure Checkout')}</h1>
                </div>

                <form onSubmit={submit} className="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
                    {/* Left Column: Details */}
                    <div className="lg:col-span-8 space-y-8">
                        {/* Shipping Address */}
                        <div className="bg-white rounded-[3rem] p-10 shadow-xl shadow-slate-200/50 border border-white">
                            <h2 className="text-xl font-black mb-8 flex items-center gap-3">
                                <span className="w-1.5 h-6 bg-indigo-500 rounded-full"></span>
                                {__('Shipping Address')}
                            </h2>
                            
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div className="md:col-span-2 space-y-2">
                                    <label className="text-xs font-bold uppercase tracking-widest text-slate-400 ml-1">{__('Full Name')}</label>
                                    <input type="text" value={data.shipping_address.name} required
                                        onChange={e => setData('shipping_address', { ...data.shipping_address, name: e.target.value })}
                                        className="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-4 text-slate-900 focus:border-indigo-500/50 transition-all outline-none" />
                                    {errors['shipping_address.name'] && <p className="text-rose-500 text-xs mt-1">{errors['shipping_address.name']}</p>}
                                </div>
                                
                                <div className="space-y-2">
                                    <label className="text-xs font-bold uppercase tracking-widest text-slate-400 ml-1">{__('Postal Code')}</label>
                                    <input type="text" value={data.shipping_address.postal_code} required
                                        onChange={e => setData('shipping_address', { ...data.shipping_address, postal_code: e.target.value })}
                                        className="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-4 text-slate-900 focus:border-indigo-500/50 transition-all outline-none" />
                                </div>

                                <div className="space-y-2">
                                    <label className="text-xs font-bold uppercase tracking-widest text-slate-400 ml-1">{__('City')}</label>
                                    <input type="text" value={data.shipping_address.city} required
                                        onChange={e => setData('shipping_address', { ...data.shipping_address, city: e.target.value })}
                                        className="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-4 text-slate-900 focus:border-indigo-500/50 transition-all outline-none" />
                                </div>

                                <div className="md:col-span-2 space-y-2">
                                    <label className="text-xs font-bold uppercase tracking-widest text-slate-400 ml-1">{__('Street Address')}</label>
                                    <input type="text" value={data.shipping_address.street} required
                                        onChange={e => setData('shipping_address', { ...data.shipping_address, street: e.target.value })}
                                        className="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-4 text-slate-900 focus:border-indigo-500/50 transition-all outline-none" />
                                </div>
                            </div>
                        </div>

                        {/* Payment Method */}
                        <div className="bg-white rounded-[3rem] p-10 shadow-xl shadow-slate-200/50 border border-white">
                            <h2 className="text-xl font-black mb-8 flex items-center gap-3">
                                <span className="w-1.5 h-6 bg-indigo-500 rounded-full"></span>
                                {__('Payment Method')}
                            </h2>
                            
                            <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                                {paymentMethods.map((method) => (
                                    <label key={method.id} className={`relative cursor-pointer group`}>
                                        <input type="radio" value={method.id} name="payment_method"
                                            checked={data.payment_method === method.id}
                                            onChange={e => setData('payment_method', e.target.value)}
                                            className="hidden" />
                                        <div className={`p-6 rounded-3xl border-2 transition-all ${data.payment_method === method.id ? 'border-indigo-600 bg-indigo-50/50 shadow-lg shadow-indigo-600/10' : 'border-slate-100 hover:border-slate-200 bg-white'}`}>
                                            <div className={`w-6 h-6 rounded-full border-2 mb-4 flex items-center justify-center transition-all ${data.payment_method === method.id ? 'border-indigo-600 bg-indigo-600' : 'border-slate-300'}`}>
                                                {data.payment_method === method.id && <div className="w-2 h-2 bg-white rounded-full"></div>}
                                            </div>
                                            <p className="font-bold text-slate-900 mb-1 tracking-tight">{method.name}</p>
                                            <p className="text-[10px] uppercase font-bold text-slate-400 tracking-wider">{__('Secure Payment')}</p>
                                        </div>
                                    </label>
                                ))}
                            </div>
                            {errors.payment_method && <p className="text-rose-500 text-xs mt-4">{errors.payment_method}</p>}
                        </div>

                        {/* Points Redemption */}
                        <div className="bg-white rounded-[3rem] p-10 shadow-xl shadow-slate-200/50 border border-white">
                            <h2 className="text-xl font-black mb-6 flex items-center gap-3">
                                <span className="w-1.5 h-6 bg-emerald-500 rounded-full"></span>
                                {__('Points Redemption')}
                            </h2>
                            <div className="flex flex-col md:flex-row items-end gap-6 bg-slate-50 p-8 rounded-[2rem] border border-slate-100">
                                <div className="flex-1 space-y-2">
                                    <div className="flex justify-between items-center mb-1">
                                        <label className="text-xs font-bold uppercase tracking-widest text-slate-400">{__('Available Points')}</label>
                                        <span className="text-xs font-black text-emerald-600">{(auth.user.points || 0).toLocaleString()} PTS</span>
                                    </div>
                                    <div className="relative">
                                        <input 
                                            type="number" 
                                            value={pointsInput}
                                            onChange={e => setPointsInput(e.target.value)}
                                            max={auth.user.points}
                                            min="0"
                                            className="w-full bg-white border border-slate-200 rounded-2xl px-5 py-4 text-slate-900 font-black focus:border-emerald-500 transition-all outline-none" 
                                            placeholder={__('Enter points to use')}
                                        />
                                        <span className="absolute right-5 top-1/2 -translate-y-1/2 text-[10px] font-black text-slate-400">PTS</span>
                                    </div>
                                </div>
                                <button 
                                    type="button"
                                    onClick={applyPoints}
                                    className="px-8 py-4 bg-slate-900 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-slate-800 transition-all"
                                >
                                    {__('Apply Points')}
                                </button>
                            </div>
                        </div>
                    </div>

                    {/* Right Column: Order Summary */}
                    <div className="lg:col-span-4 sticky top-12 space-y-8">
                        <div className="bg-slate-900 rounded-[3rem] p-10 text-white shadow-2xl shadow-slate-900/40 relative overflow-hidden">
                            <h2 className="text-2xl font-black mb-8 tracking-tighter">{__('Summary')}</h2>
                            
                            <div className="space-y-4 mb-8 max-h-60 overflow-y-auto pr-2 custom-scrollbar">
                                {items.map((item, idx) => (
                                    <div key={idx} className="flex gap-4 items-center group">
                                        <div className="w-12 h-12 bg-white/5 rounded-xl overflow-hidden flex-shrink-0 group-hover:scale-110 transition-transform">
                                            {item.variant.product.images?.length > 0 && 
                                                <img src={`/storage/${item.variant.product.images[0].file_path}`} className="w-full h-full object-cover" />
                                            }
                                        </div>
                                        <div className="flex-1 min-w-0">
                                            <p className="text-[10px] font-black truncate uppercase tracking-tight">{item.variant.product.name}</p>
                                            <p className="text-[9px] text-slate-500 font-bold">{__('QTY:')} {item.quantity}</p>
                                        </div>
                                        <p className="text-xs font-black">¥{item.line_total.toLocaleString()}</p>
                                    </div>
                                ))}
                            </div>
                             <div className="space-y-4 border-t border-white/10 pt-8 mb-8">
                                <div className="flex justify-between text-slate-400 text-xs font-bold uppercase tracking-widest">
                                    <span>{__('Subtotal')}</span>
                                    <span className="text-white">¥{subtotal.toLocaleString()}</span>
                                </div>
                                
                                {discount_amount > 0 && (
                                    <div className="flex justify-between text-emerald-400 text-xs font-black uppercase tracking-widest italic">
                                        <span>{__('Discount')} ({discount_label})</span>
                                        <span>-¥{discount_amount.toLocaleString()}</span>
                                    </div>
                                )}

                                <div className="flex justify-between text-slate-400 text-xs font-bold uppercase tracking-widest">
                                    <span>{__('Estimated Tax')}</span>
                                    <span className="text-white">¥{tax.toLocaleString()}</span>
                                </div>

                                {points_discount > 0 && (
                                    <div className="flex justify-between text-yellow-400 text-xs font-black uppercase tracking-widest italic pt-2 border-t border-white/5 mt-2">
                                        <span>{__('Points Used')}</span>
                                        <span>-¥{points_discount.toLocaleString()}</span>
                                    </div>
                                )}

                                <div className="flex justify-between items-end pt-4">
                                    <span className="font-black text-slate-400 uppercase tracking-[0.2em] text-[10px]">{__('Total Amount')}</span>
                                    <span className="text-3xl font-black text-indigo-400 tracking-tighter shadow-glow-indigo">¥{total.toLocaleString()}</span>
                                </div>
                            </div>

                            <button type="submit" disabled={processing} className="w-full bg-indigo-600 text-white py-6 rounded-2xl font-black tracking-widest uppercase hover:bg-indigo-500 transition-all active:scale-[0.98] disabled:opacity-50 shadow-xl shadow-indigo-600/20">
                                {processing ? __('Processing...') : __('Place Order Now')}
                            </button>
                        </div>
                        
                        <div className="bg-white rounded-[2.5rem] p-8 border border-slate-100 flex gap-4 shadow-sm">
                            <div className="text-indigo-600 flex-shrink-0">
                                <svg className="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fillRule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clipRule="evenodd" /></svg>
                            </div>
                            <p className="text-[10px] font-bold text-slate-500 leading-relaxed uppercase tracking-widest">
                                {__('Your payment is secured via end-to-end encryption. Order fulfillment starts immediately after confirmation.')}
                            </p>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    );
}
