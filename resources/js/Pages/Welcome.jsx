import { Head, Link } from '@inertiajs/react';
import ProductCard from '@/Components/Store/ProductCard';
import ShopLayout from '@/Layouts/ShopLayout';
import { useTranslation } from '@/Helpers/translations';

export default function Welcome({ featuredProducts, canLogin, canRegister }) {
    const { __ } = useTranslation();
    return (
        <ShopLayout>
            <Head title={`EC-NEXT | ${__('Future of E-Commerce')}`} />

            {/* Hero Section */}
            <header className="relative pt-32 pb-20 px-6 overflow-hidden">
                <div className="max-w-7xl mx-auto grid lg:grid-cols-2 gap-12 items-center">
                    <div className="relative z-10">
                        <span className="inline-block bg-indigo-50 text-indigo-600 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest mb-6">
                            {__('Next Generation OSS')}
                        </span>
                        <h1 className="text-7xl font-black leading-[1.05] mb-8">
                            {__('High Fidelity')} <br/> 
                            <span className="text-indigo-600 italic">{__('Evolution.')}</span>
                        </h1>
                        <p className="text-xl text-slate-500 max-w-lg mb-10 leading-relaxed">
                            {__('A seamless fusion of enterprise power and bespoke flexibility. Built for the modern commerce era.')}
                        </p>
                        <div className="flex gap-4">
                            <button className="bg-slate-900 text-white px-8 py-4 rounded-2xl font-bold hover:bg-slate-800 transition-all shadow-xl shadow-slate-900/20">
                                {__('Explore Collection')}
                            </button>
                            <button className="border-2 border-slate-100 bg-white px-8 py-4 rounded-2xl font-bold hover:bg-slate-50 transition-all">
                                {__('Custom Blocks')}
                            </button>
                        </div>
                    </div>
                    <div className="relative h-[600px] rounded-[3rem] overflow-hidden shadow-2xl">
                         <img 
                            src="https://images.unsplash.com/photo-1441986300917-64674bd600d8?auto=format&fit=crop&q=80&w=1200" 
                            className="w-full h-full object-cover"
                            alt="Luxury Store"
                         />
                         <div className="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/60 to-transparent p-12 text-white">
                             <p className="text-indigo-400 font-bold uppercase tracking-widest text-xs mb-2">{__('Editor\'s Choice')}</p>
                             <h4 className="text-2xl font-bold">{__('2026 Collection Out Now')}</h4>
                         </div>
                    </div>
                </div>
            </header>

            {/* Featured Products */}
            <section className="py-24 bg-slate-50 px-6">
                <div className="max-w-7xl mx-auto">
                    <div className="flex justify-between items-end mb-16">
                        <div>
                            <h2 className="text-4xl font-black mb-4">{__('Curated Essentials')}</h2>
                            <p className="text-slate-500">{__('Discover handpicked items that redefine quality.')}</p>
                        </div>
                        <a href="#" className="font-bold border-b-2 border-slate-900 pb-1">{__('Shop Collection')}</a>
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-x-8 gap-y-12">
                        {featuredProducts.length > 0 ? (
                            featuredProducts.map((product) => (
                                <ProductCard key={product.id} product={product} />
                            ))
                        ) : (
                            <p className="col-span-full text-center py-20 text-slate-400">{__('Loading fine collections...')}</p>
                        )}
                    </div>
                </div>
            </section>

            {/* Admin Access Hint */}
            <aside className="fixed bottom-8 left-8 z-50">
                <a href="/admin" className="glass bg-white/50 backdrop-blur p-4 rounded-2xl border border-slate-200 shadow-2xl flex items-center gap-3 hover:scale-105 transition-transform group">
                    <div className="w-10 h-10 bg-slate-900 rounded-xl flex items-center justify-center text-white">
                        <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div>
                        <p className="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">{__('Quick Access')}</p>
                        <p className="text-xs font-bold text-slate-900 group-hover:text-indigo-600 transition-colors uppercase tracking-widest">{__('Admin Dashboard')} &rarr;</p>
                    </div>
                </a>
            </aside>
        </ShopLayout>
    );
}
