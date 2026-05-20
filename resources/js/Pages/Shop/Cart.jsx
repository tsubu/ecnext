import { useState } from 'react';
import { Head, Link, useForm, usePage } from '@inertiajs/react';
import ShopLayout from '@/Layouts/ShopLayout';
import { useTranslation } from '@/Helpers/translations';

export default function Cart({ cart }) {
    const { __ } = useTranslation();
    const { items, subtotal, discount_amount, discount_label, total, coupon } = cart;
    const { patch, delete: destroy, post } = useForm();
    const [couponCode, setCouponCode] = useState('');
    const { flash } = usePage().props;

    // Simple tax calculation (10% of subtotal)
    const tax = Math.round(subtotal * 0.1);

    const updateQuantity = (variantId, quantity) => {
        if (quantity < 1) return;
        // The endpoint expects product_variant_id as per our controller logic update
        post(route('cart.store', { product_id: items.find(i => i.variant.id === variantId).variant.product_id, product_variant_id: variantId, quantity }));
    };

    const removeItem = (variantId) => {
        if (confirm(__('Are you sure you want to remove this item from your cart?'))) {
            destroy(route('cart.destroy', { product: items.find(i => i.variant.id === variantId).variant.product_id, product_variant_id: variantId }));
        }
    };

    const applyCoupon = (e) => {
        e.preventDefault();
        post(route('cart.coupon.apply', { code: couponCode }), {
            onSuccess: () => setCouponCode(''),
        });
    };

    const removeCoupon = () => {
        destroy(route('cart.coupon.remove'));
    };

    return (
        <ShopLayout>
            <Head title={`${__('Shopping Bag')} | EC-NEXT`} />
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                {flash.success && (
                    <div className="mb-8 p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl text-emerald-600 text-sm font-bold flex items-center gap-3">
                        <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 13l4 4L19 7"/></svg>
                        {flash.success}
                    </div>
                )}

                {flash.error && (
                    <div className="mb-8 p-4 bg-rose-500/10 border border-rose-500/20 rounded-2xl text-rose-600 text-sm font-bold flex items-center gap-3">
                        <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {flash.error}
                    </div>
                )}

                {items.length === 0 ? (
                    <div className="bg-white rounded-[3rem] p-24 text-center shadow-xl shadow-slate-200/50">
                        <div className="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-8 text-slate-300">
                            <svg className="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        </div>
                        <h2 className="text-2xl font-bold text-slate-900 mb-4">{__('Your bag is empty')}</h2>
                        <p className="text-slate-500 mb-10 max-w-sm mx-auto">{__('Looks like you haven\'t added anything to your cart yet. Browse our collections to find something you love.')}</p>
                        <Link href={route('home')} className="inline-flex items-center px-10 py-5 bg-indigo-600 border border-transparent rounded-2xl font-bold text-white tracking-widest hover:bg-indigo-700 active:scale-95 transition-all shadow-xl shadow-indigo-600/30">
                            {__('Back to Shop')}
                        </Link>
                    </div>
                ) : (
                    <div className="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
                        {/* Cart Items List */}
                        <div className="lg:col-span-8 space-y-6">
                            {items.map((item, idx) => (
                                <div key={idx} className="bg-white rounded-[2rem] p-8 shadow-sm flex flex-col md:flex-row items-center gap-8 border border-white hover:border-indigo-100 transition-all hover:shadow-xl hover:shadow-slate-200/30 group">
                                    <div className="w-32 h-32 bg-slate-100 rounded-2xl overflow-hidden shadow-inner flex-shrink-0">
                                        {item.variant.product.images?.length > 0 ? (
                                            <img src={`/storage/${item.variant.product.images[0].file_path}`} alt={item.variant.product.name} className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                                        ) : (
                                            <div className="items-center justify-center text-slate-300 h-full flex">
                                                <svg className="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                            </div>
                                        )}
                                    </div>

                                    <div className="flex-1 min-w-0">
                                        <div className="flex justify-between items-start mb-2">
                                            <div>
                                                <h3 className="text-lg font-black text-slate-900 group-hover:text-indigo-600 transition-colors uppercase tracking-tight">{item.variant.product.name}</h3>
                                                <p className="text-[10px] font-bold text-slate-400 mt-1 uppercase tracking-widest">
                                                    {item.variant.option1_value} {item.variant.option2_value ? `/ ${item.variant.option2_value}` : ''}
                                                </p>
                                            </div>
                                            <div className="text-right">
                                                <p className="text-lg font-bold text-slate-900">¥{item.price.toLocaleString()}</p>
                                                {item.variant.compare_at_price > item.price && (
                                                    <p className="text-xs text-slate-400 line-through">¥{parseFloat(item.variant.compare_at_price).toLocaleString()}</p>
                                                )}
                                            </div>
                                        </div>
                                        <p className="text-[9px] font-bold text-indigo-500 uppercase tracking-widest mb-6">SKU: {item.variant.sku}</p>
                                        
                                        <div className="flex items-center justify-between">
                                            <div className="flex items-center bg-slate-50 rounded-xl p-1 border border-slate-100">
                                                <button onClick={() => updateQuantity(item.variant.id, item.quantity - 1)} className="w-10 h-10 flex items-center justify-center text-slate-400 hover:text-indigo-600 transition-colors">
                                                    <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M20 12H4"/></svg>
                                                </button>
                                                <span className="w-10 text-center font-bold text-slate-900">{item.quantity}</span>
                                                <button onClick={() => updateQuantity(item.variant.id, item.quantity + 1)} className="w-10 h-10 flex items-center justify-center text-slate-400 hover:text-indigo-600 transition-colors">
                                                    <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 4v16m8-8H4"/></svg>
                                                </button>
                                            </div>
                                            <button onClick={() => removeItem(item.variant.id)} className="text-xs font-bold text-slate-400 hover:text-rose-500 transition-colors flex items-center gap-1.5 p-2 tracking-widest">
                                                <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                {__('REMOVE')}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            ))}
                        </div>

                        {/* Summary Side Column */}
                        <div className="lg:col-span-4 sticky top-12 space-y-6">
                            <div className="bg-slate-900 rounded-[3rem] p-10 text-white shadow-2xl shadow-slate-900/40 relative overflow-hidden">
                                <div className="absolute top-0 right-0 w-64 h-64 bg-indigo-500/10 rounded-full -mr-32 -mt-32 blur-3xl"></div>
                                
                                <h2 className="text-2xl font-black mb-10 tracking-tighter">{__('Order Summary')}</h2>
                                
                                <div className="space-y-6 mb-10">
                                    <div className="flex justify-between text-slate-400 font-medium">
                                        <span>{__('Subtotal')}</span>
                                        <span className="text-white font-bold">¥{subtotal.toLocaleString()}</span>
                                    </div>
                                    
                                    {discount_amount > 0 && (
                                        <div className="flex justify-between text-emerald-400 font-bold bg-emerald-500/5 p-4 rounded-2xl border border-emerald-500/10">
                                            <div className="flex flex-col">
                                                <span>{__('Discount')}</span>
                                                <span className="text-[10px] uppercase tracking-widest">{discount_label}</span>
                                            </div>
                                            <span>-¥{discount_amount.toLocaleString()}</span>
                                        </div>
                                    )}

                                    <div className="flex justify-between text-slate-400 font-medium">
                                        <span>{__('Estimated Tax')} (10%)</span>
                                        <span className="text-white">¥{tax.toLocaleString()}</span>
                                    </div>
                                    
                                    <div className="h-px bg-white/10 my-8"></div>
                                    <div className="flex justify-between items-end">
                                        <span className="text-lg font-bold">{__('Total Amount')}</span>
                                        <span className="text-3xl font-black text-indigo-400 tracking-tighter">¥{total.toLocaleString()}</span>
                                    </div>
                                </div>

                                <Link href={route('home')} className="block w-full bg-white text-slate-900 text-center py-6 rounded-[1.5rem] font-black tracking-widest uppercase hover:bg-slate-100 transition-all active:scale-[0.98] shadow-xl shadow-white/5 mb-6">
                                    {__('Secure Checkout')}
                                </Link>
                                
                                <div className="flex items-center justify-center gap-4 text-slate-500">
                                    <svg className="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fillRule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clipRule="evenodd" /></svg>
                                    <span className="text-[10px] font-bold uppercase tracking-widest">{__('Encrypted SSL Payment')}</span>
                                </div>
                            </div>

                            <div className="bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm">
                                <h3 className="text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-4">{__('Promo Code')}</h3>
                                {coupon ? (
                                    <div className="flex items-center justify-between p-4 bg-indigo-50 rounded-2xl border border-indigo-100">
                                        <div className="flex items-center gap-3">
                                            <div className="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center text-white">
                                                <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                            </div>
                                            <span className="text-sm font-black text-indigo-600 tracking-tighter uppercase">{coupon.code}</span>
                                        </div>
                                        <button onClick={removeCoupon} className="text-[10px] font-black text-indigo-400 hover:text-rose-500 uppercase tracking-widest transition-colors">
                                            {__('Remove')}
                                        </button>
                                    </div>
                                ) : (
                                    <form onSubmit={applyCoupon} className="flex gap-2">
                                        <input 
                                            type="text" 
                                            value={couponCode}
                                            onChange={(e) => setCouponCode(e.target.value.toUpperCase())}
                                            placeholder={__('Enter code...')}
                                            className="flex-1 bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold uppercase tracking-widest focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all"
                                        />
                                        <button type="submit" className="px-6 py-3 bg-slate-900 text-white text-[11px] font-black uppercase tracking-widest rounded-xl hover:bg-slate-800 active:scale-95 transition-all">
                                            {__('Apply')}
                                        </button>
                                    </form>
                                )}
                            </div>
                        </div>
                    </div>
                )}
            </div>
        </ShopLayout>
    );
}
