import React from 'react';
import { Head, useForm, Link } from '@inertiajs/react';

export default function Create({ product, orderId }) {
    const { data, setData, post, processing, errors, reset } = useForm({
        rating: 5,
        comment: '',
        order_id: orderId,
    });

    const submit = (e) => {
        e.preventDefault();
        post(route('products.reviews.store', product.slug));
    };

    return (
        <div className="min-h-screen bg-[#0a0a0a] text-white p-6 md:p-20 font-sans">
            <Head title={`Review: ${product.name}`} />
            
            <div className="max-w-2xl mx-auto space-y-12">
                <header className="space-y-4">
                    <Link href={route('products.show', product.slug)} className="text-cyan-400 text-xs font-bold uppercase tracking-widest hover:text-white transition-colors flex items-center gap-2">
                        <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M15 19l-7-7 7-7"/></svg>
                        Back to Gear
                    </Link>
                    <h1 className="text-4xl md:text-6xl font-black italic tracking-tighter uppercase border-l-8 border-cyan-500 pl-6">Neural_Log // Post</h1>
                </header>

                <div className="p-8 bg-white/5 border border-white/10 rounded-3xl space-y-8 relative overflow-hidden">
                    <div className="flex items-center gap-4">
                        <img src={product.images?.[0]?.url || 'https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&q=80&w=400'} alt={product.name} className="w-16 h-16 object-cover rounded-xl border border-white/10" />
                        <div>
                            <p className="text-[10px] font-bold text-slate-500 uppercase tracking-[0.2em] mb-1">Target_Subject</p>
                            <h3 className="text-xl font-bold uppercase tracking-tighter">{product.name}</h3>
                        </div>
                    </div>

                    <form onSubmit={submit} className="space-y-10">
                        {/* Rating Selection */}
                        <div className="space-y-4">
                            <label className="text-[10px] font-bold text-cyan-400 uppercase tracking-[0.3em]">Rating_Feedback_Value</label>
                            <div className="flex gap-4">
                                {[1, 2, 3, 4, 5].map((star) => (
                                    <button
                                        key={star}
                                        type="button"
                                        onClick={() => setData('rating', star)}
                                        className={`w-12 h-12 flex items-center justify-center rounded-2xl border-2 transition-all ${data.rating >= star ? 'bg-cyan-500/10 border-cyan-500 text-cyan-400 shadow-[0_0_15px_rgba(0,251,251,0.2)]' : 'bg-white/5 border-white/10 text-slate-700 hover:border-white/30'}`}
                                    >
                                        <svg className="w-6 h-6" fill={data.rating >= star ? 'currentColor' : 'none'} stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.382-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                                    </button>
                                ))}
                            </div>
                            {errors.rating && <p className="text-rose-500 text-[10px] font-bold uppercase italic">{errors.rating}</p>}
                        </div>

                        {/* Comment Input */}
                        <div className="space-y-4">
                            <label className="text-[10px] font-bold text-cyan-400 uppercase tracking-[0.3em]">Comment_Data_Input</label>
                            <textarea
                                value={data.comment}
                                onChange={(e) => setData('comment', e.target.value)}
                                className="w-full bg-black/40 border-2 border-white/10 rounded-3xl p-6 text-white text-sm focus:border-cyan-500 focus:ring-4 focus:ring-cyan-500/10 outline-none transition-all placeholder:text-slate-800"
                                rows="6"
                                placeholder="Describe your neural synchronization experience..."
                            />
                            {errors.comment && <p className="text-rose-500 text-[10px] font-bold uppercase italic">{errors.comment}</p>}
                        </div>

                        <button
                            type="submit"
                            disabled={processing}
                            className="w-full py-6 bg-cyan-500 hover:bg-white text-black font-black text-xl uppercase tracking-widest transition-all disabled:opacity-50 active:scale-95 shadow-[0_0_30px_rgba(0,251,251,0.2)]"
                        >
                            Submit_Transmission
                        </button>
                    </form>
                </div>

                <footer className="text-center opacity-30">
                    <p className="text-[8px] font-bold uppercase tracking-[1em] text-slate-500">End_Encryption_Protocol</p>
                </footer>
            </div>
        </div>
    );
}
