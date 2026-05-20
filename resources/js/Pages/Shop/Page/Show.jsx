import React from 'react';
import { Head } from '@inertiajs/react';
import ShopLayout from '@/Layouts/ShopLayout';
import { useTranslation } from '@/Helpers/translations';

export default function Show({ page, html_content }) {
    const { __ } = useTranslation();

    return (
        <ShopLayout>
            <Head title={`${page.title} | EC-NEXT`} />
            
            <div className="bg-slate-50 min-h-screen pt-32 pb-24 px-6 relative overflow-hidden">
                {/* Background Decorations */}
                <div className="absolute top-0 right-0 w-1/2 h-1/2 bg-indigo-500/5 blur-[120px] rounded-full -mr-64 -mt-64"></div>
                <div className="absolute bottom-0 left-0 w-1/2 h-1/2 bg-rose-500/5 blur-[120px] rounded-full -ml-64 -mb-64"></div>

                <div className="max-w-4xl mx-auto relative z-10">
                    <header className="mb-16">
                        <nav className="flex items-center gap-2 mb-8 text-[10px] font-black uppercase tracking-widest text-slate-400">
                            <a href={route('home')} className="hover:text-indigo-600 transition-colors">Home</a>
                            <span>/</span>
                            <span className="text-slate-900">{page.title}</span>
                        </nav>
                        <h1 className="text-6xl font-black text-slate-900 tracking-tighter mb-6 leading-none">
                            {page.title}
                        </h1>
                        <div className="w-24 h-2 bg-indigo-600 rounded-full"></div>
                    </header>

                    {page.type === 'legal' && page.legal_data ? (
                        <div className="space-y-12">
                            <div className="grid grid-cols-1 md:grid-cols-3 gap-8 py-10 border-b border-slate-200">
                                <h3 className="text-sm font-black uppercase tracking-widest text-slate-400">{__('Operating Manager')}</h3>
                                <p className="md:col-span-2 text-lg font-bold text-slate-900">{page.legal_data.operating_manager}</p>
                            </div>
                            <div className="grid grid-cols-1 md:grid-cols-3 gap-8 py-10 border-b border-slate-200">
                                <h3 className="text-sm font-black uppercase tracking-widest text-slate-400">{__('Official Business Address')}</h3>
                                <p className="md:col-span-2 text-lg font-bold text-slate-900">{page.legal_data.address}</p>
                            </div>
                            <div className="grid grid-cols-1 md:grid-cols-3 gap-8 py-10 border-b border-slate-200">
                                <h3 className="text-sm font-black uppercase tracking-widest text-slate-400">{__('Official Contact Phone')}</h3>
                                <p className="md:col-span-2 text-lg font-bold text-slate-900">{page.legal_data.phone}</p>
                            </div>
                            <div className="grid grid-cols-1 md:grid-cols-3 gap-8 py-10 border-b border-slate-200">
                                <h3 className="text-sm font-black uppercase tracking-widest text-slate-400">{__('Additional Fees (Price Info)')}</h3>
                                <p className="md:col-span-2 text-slate-600 leading-relaxed font-medium">{page.legal_data.additional_fees}</p>
                            </div>
                            <div className="grid grid-cols-1 md:grid-cols-3 gap-8 py-10 border-b border-slate-200">
                                <h3 className="text-sm font-black uppercase tracking-widest text-slate-400">{__('Payment Terms & Methods')}</h3>
                                <p className="md:col-span-2 text-slate-600 leading-relaxed font-medium">{page.legal_data.payment_methods}</p>
                            </div>
                            <div className="grid grid-cols-1 md:grid-cols-3 gap-8 py-10 border-b border-slate-200">
                                <h3 className="text-sm font-black uppercase tracking-widest text-slate-400">{__('Delivery Timeframes')}</h3>
                                <p className="md:col-span-2 text-slate-600 leading-relaxed font-medium">{page.legal_data.delivery_time}</p>
                            </div>
                            <div className="grid grid-cols-1 md:grid-cols-3 gap-8 py-10">
                                <h3 className="text-sm font-black uppercase tracking-widest text-slate-400">{__('Return & Exchange Policy')}</h3>
                                <p className="md:col-span-2 text-slate-600 leading-relaxed font-medium">{page.legal_data.returns}</p>
                            </div>
                        </div>
                    ) : (
                        <article className="prose prose-slate prose-xl max-w-none prose-headings:font-black prose-headings:tracking-tighter prose-p:font-medium prose-p:leading-relaxed text-slate-600">
                            {html_content ? (
                                <div dangerouslySetInnerHTML={{ __html: html_content }} />
                            ) : (
                                <p>{page.content}</p>
                            )}
                        </article>
                    )}
                </div>
            </div>
        </ShopLayout>
    );
}
