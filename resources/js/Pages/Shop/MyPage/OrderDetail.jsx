import { Head, Link } from '@inertiajs/react';
import { useTranslation } from '@/Helpers/translations';

const OrderDetail = ({ order }) => {
    const { __ } = useTranslation();
    const shippingAddress = order.addresses.find(a => a.type === 'shipping');

    return (
        <div className="min-h-screen bg-[#050505] text-white py-20 px-4">
            <Head title={`${__('Order')} ${order.order_number}`} />

            <div className="max-w-4xl mx-auto">
                <Link 
                    href={route('mypage.orders')}
                    className="inline-flex items-center gap-2 text-slate-500 hover:text-white font-bold text-xs uppercase tracking-widest mb-12 transition-all"
                >
                    <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                    {__('Back to History')}
                </Link>

                <div className="flex flex-wrap items-end justify-between gap-6 mb-12">
                    <div className="space-y-2">
                        <span className="px-4 py-1.5 bg-emerald-500/20 text-emerald-400 rounded-full text-[10px] font-black uppercase tracking-widest">
                            {__(order.status)}
                        </span>
                        <h1 className="text-4xl font-black tracking-tighter">{__('ORDER')} #{order.order_number}</h1>
                        <p className="text-slate-500 font-bold">{new Date(order.created_at).toLocaleString()}</p>
                    </div>
                </div>

                <div className="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
                    <div className="md:col-span-2 space-y-6">
                        <div className="glass p-8 rounded-[2rem] border border-white/5 bg-white/5">
                            <h3 className="text-xs font-black text-slate-500 uppercase tracking-[0.2em] mb-8">{__('Ordered Items')}</h3>
                            <div className="space-y-6">
                                {order.items.map((item) => (
                                    <div key={item.id} className="flex gap-6 pb-6 border-b border-white/5 last:border-0 last:pb-0">
                                        <div className="w-20 h-20 bg-slate-800 rounded-2xl flex-none overflow-hidden border border-white/5">
                                            {/* Item image */}
                                        </div>
                                        <div className="flex-grow">
                                            <h4 className="font-bold text-lg leading-tight mb-1">{item.product_name}</h4>
                                            <p className="text-slate-500 text-xs font-bold uppercase tracking-widest mb-4">{item.variant?.option1_value} {item.variant?.option2_value}</p>
                                            <div className="flex justify-between items-center">
                                                <p className="text-sm font-bold text-slate-400">¥{Math.round(item.unit_price).toLocaleString()} × {item.quantity}</p>
                                                <p className="font-black text-white">¥{Math.round(item.total_price).toLocaleString()}</p>
                                            </div>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </div>

                        {order.shipments && order.shipments.length > 0 && (
                            <div className="glass p-8 rounded-[2rem] border border-white/5 bg-white/5">
                                <h3 className="text-xs font-black text-slate-500 uppercase tracking-[0.2em] mb-8">{__('Shipping Info')}</h3>
                                {order.shipments.map((shipment) => (
                                    <div key={shipment.id} className="flex flex-wrap justify-between gap-4 p-6 bg-white/5 rounded-3xl">
                                        <div>
                                            <p className="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">{__('Carrier')}</p>
                                            <p className="font-bold">{shipment.carrier}</p>
                                        </div>
                                        <div>
                                            <p className="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">{__('Tracking Number')}</p>
                                            <p className="font-black text-emerald-400">{shipment.tracking_number}</p>
                                        </div>
                                        <div>
                                            <p className="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">{__('Shipped At')}</p>
                                            <p className="font-bold">{shipment.shipped_at ? new Date(shipment.shipped_at).toLocaleDateString() : __('Pending')}</p>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        )}
                    </div>

                    <div className="space-y-8">
                        <div className="glass p-8 rounded-[2rem] border border-white/5 bg-white/5">
                            <h3 className="text-xs font-black text-slate-500 uppercase tracking-[0.2em] mb-8">{__('Summary')}</h3>
                            <div className="space-y-4">
                                <div className="flex justify-between text-sm">
                                    <span className="text-slate-500 font-bold">{__('Subtotal')}</span>
                                    <span className="font-bold">¥{Math.round(order.item_total).toLocaleString()}</span>
                                </div>
                                <div className="flex justify-between text-sm">
                                    <span className="text-slate-500 font-bold">{__('Tax')}</span>
                                    <span className="font-bold">¥{Math.round(order.tax).toLocaleString()}</span>
                                </div>
                                <div className="flex justify-between text-sm">
                                    <span className="text-slate-500 font-bold">{__('Shipping')}</span>
                                    <span className="font-bold">¥{Math.round(order.shipping_fee).toLocaleString()}</span>
                                </div>
                                {order.points_discount > 0 && (
                                    <div className="flex justify-between text-sm text-emerald-400">
                                        <span className="font-bold">{__('Points Discount')}</span>
                                        <span className="font-black">-¥{Math.round(order.points_discount).toLocaleString()}</span>
                                    </div>
                                )}
                                <div className="pt-6 border-t border-white/5 flex justify-between">
                                    <span className="font-black text-xs uppercase tracking-widest mt-1">{__('Total Amount')}</span>
                                    <span className="text-2xl font-black text-white">¥{Math.round(order.total_price).toLocaleString()}</span>
                                </div>
                            </div>
                        </div>

                        {shippingAddress && (
                            <div className="glass p-8 rounded-[2rem] border border-white/5 bg-white/5">
                                <h3 className="text-xs font-black text-slate-500 uppercase tracking-[0.2em] mb-6">{__('Delivery Address')}</h3>
                                <div className="space-y-2">
                                    <p className="font-black text-white">{shippingAddress.name}</p>
                                    <p className="text-sm font-bold text-slate-500 tracking-tight leading-relaxed">
                                        〒{shippingAddress.postal_code}<br />
                                        {shippingAddress.city} {shippingAddress.street} {shippingAddress.address_line2}
                                    </p>
                                </div>
                            </div>
                        )}
                    </div>
                </div>
            </div>
        </div>
    );
};

export default OrderDetail;
