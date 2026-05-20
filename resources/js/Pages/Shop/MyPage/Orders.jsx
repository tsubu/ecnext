import { Head, Link } from '@inertiajs/react';
import { useTranslation } from '@/Helpers/translations';

const Orders = ({ orders }) => {
    const { __ } = useTranslation();

    return (
        <div className="min-h-screen bg-[#050505] text-white py-20 px-4">
            <Head title={__('Order History')} />
            
            <div className="max-w-6xl mx-auto">
                <header className="mb-12">
                    <h1 className="text-4xl font-black tracking-tighter mb-2">{__('MY ORDERS')}</h1>
                    <p className="text-slate-500 uppercase tracking-widest text-xs font-bold">{__('Track and manage your purchases')}</p>
                </header>

                <div className="grid gap-6">
                    {orders.data.map((order) => (
                        <div key={order.id} className="group relative bg-white/5 border border-white/5 rounded-[2rem] p-8 overflow-hidden transition-all hover:bg-white/[0.07] hover:border-emerald-500/30">
                            <div className="absolute top-0 right-0 w-64 h-64 bg-emerald-500/5 blur-[80px] -z-10 transition-all group-hover:bg-emerald-500/10"></div>
                            
                            <div className="flex flex-wrap items-center justify-between gap-6 mb-8">
                                <div className="space-y-1">
                                    <span className="text-[10px] font-black text-slate-500 uppercase tracking-widest">{__('Order Number')}</span>
                                    <p className="text-lg font-black tracking-tight">{order.order_number}</p>
                                </div>
                                <div className="space-y-1">
                                    <span className="text-[10px] font-black text-slate-500 uppercase tracking-widest">{__('Date')}</span>
                                    <p className="font-bold text-slate-300">{new Date(order.created_at).toLocaleDateString()}</p>
                                </div>
                                <div className="space-y-1">
                                    <span className="text-[10px] font-black text-slate-500 uppercase tracking-widest">{__('Total')}</span>
                                    <p className="text-xl font-black text-emerald-400">¥{Math.round(order.total_price).toLocaleString()}</p>
                                </div>
                                <div className="space-y-1">
                                    <span className="text-[10px] font-black text-slate-500 uppercase tracking-widest">{__('Status')}</span>
                                    <span className={`px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest ${
                                        order.status === 'completed' ? 'bg-emerald-500/20 text-emerald-400' : 
                                        order.status === 'processing' ? 'bg-blue-500/20 text-blue-400' : 'bg-slate-700/50 text-slate-400'
                                    }`}>
                                        {__(order.status)}
                                    </span>
                                </div>
                                <div>
                                    <Link 
                                        href={route('mypage.orders.detail', order.id)}
                                        className="px-8 py-3 bg-white text-black font-black rounded-full text-xs transition-all hover:scale-105 active:scale-95 shadow-xl shadow-white/5"
                                    >
                                        {__('DETAILS')}
                                    </Link>
                                </div>
                            </div>

                            <div className="flex gap-4 overflow-x-auto pb-2 scrollbar-hide">
                                {order.items.map((item) => (
                                    <div key={item.id} className="flex-none w-16 h-16 bg-white/10 rounded-xl overflow-hidden border border-white/5">
                                        {/* Mock image for now since we're focused on logic */}
                                        <div className="w-full h-full bg-gradient-to-br from-slate-700 to-slate-900"></div>
                                    </div>
                                ))}
                                {order.items.length > 5 && (
                                    <div className="flex-none w-16 h-16 bg-white/5 rounded-xl border border-white/5 flex items-center justify-center text-[10px] font-black text-slate-500">
                                        +{order.items.length - 5}
                                    </div>
                                )}
                            </div>
                        </div>
                    ))}
                </div>

                {orders.data.length === 0 && (
                    <div className="text-center py-20 bg-white/5 rounded-[3rem] border border-dashed border-white/10">
                        <p className="text-slate-500 font-bold mb-6">{__("You haven't placed any orders yet.")}</p>
                        <Link 
                            href={route('home')}
                            className="px-12 py-4 bg-emerald-600 text-white font-black rounded-2xl hover:bg-emerald-500 transition-all inline-block"
                        >
                            {__('START SHOPPING')}
                        </Link>
                    </div>
                )}
            </div>
        </div>
    );
};

export default Orders;
