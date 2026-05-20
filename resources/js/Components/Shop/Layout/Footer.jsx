import React from 'react';
import { Link } from '@inertiajs/react';
import { useTranslation } from '@/Helpers/translations';

export default function Footer() {
    const { __ } = useTranslation();
    const currentYear = new Date().getFullYear();

    return (
        <footer className="bg-slate-900 pt-24 pb-12 text-white overflow-hidden relative">
            <div className="absolute top-0 left-0 w-full h-px bg-gradient-to-r from-transparent via-indigo-500/50 to-transparent"></div>
            <div className="absolute -top-24 left-1/2 -translate-x-1/2 w-[500px] h-24 bg-indigo-600/20 blur-[120px] rounded-full"></div>
            
            <div className="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-4 gap-16 mb-24 relative z-10">
                <div className="col-span-1 md:col-span-1">
                    <div className="flex items-center gap-3 mb-8">
                        <div className="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center text-white font-black shadow-xl shadow-indigo-600/40">N</div>
                        <h2 className="text-xl font-black tracking-tighter uppercase">EC-NEXT</h2>
                    </div>
                    <p className="text-slate-400 text-sm leading-relaxed font-medium mb-8">
                        {__('A seamless fusion of enterprise power and bespoke flexibility. Built for the modern commerce era.')}
                    </p>
                    <div className="flex gap-4">
                        {['twitter', 'instagram', 'facebook'].map((social) => (
                            <a key={social} href="#" className="w-10 h-10 bg-white/5 hover:bg-white/10 rounded-xl flex items-center justify-center transition-all group">
                                <div className="w-5 h-5 bg-slate-500 group-hover:bg-indigo-400 transition-colors" style={{ maskImage: `url(/icons/${social}.svg)`, maskSize: 'contain' }}></div>
                            </a>
                        ))}
                    </div>
                </div>

                <div>
                    <h3 className="text-xs font-black text-indigo-400 uppercase tracking-[0.2em] mb-8">{__('Shop')}</h3>
                    <ul className="space-y-4 text-sm font-bold text-slate-400">
                        <li><Link href={route('home')} className="hover:text-white transition-colors">{__('New Seasonal Collection')}</Link></li>
                        <li><Link href="#" className="hover:text-white transition-colors">{__('Best Sellers')}</Link></li>
                        <li><Link href="#" className="hover:text-white transition-colors">{__('Shop All')}</Link></li>
                        <li><Link href="#" className="hover:text-white transition-colors text-rose-400 group flex items-center gap-2">{__('Summer Sale')} <span className="text-[10px] bg-rose-500/20 px-2 py-0.5 rounded text-rose-500 animate-pulse">HOT</span></Link></li>
                    </ul>
                </div>

                <div>
                    <h3 className="text-xs font-black text-indigo-400 uppercase tracking-[0.2em] mb-8">{__('Support')}</h3>
                    <ul className="space-y-4 text-sm font-bold text-slate-400">
                        <li><Link href={route('inquiry.index')} className="hover:text-white transition-colors">{__('Contact Us')}</Link></li>
                        <li><Link href={route('pages.show', { slug: 'privacy-policy' })} className="hover:text-white transition-colors">{__('Privacy Policy')}</Link></li>
                        <li><Link href={route('legal.trade-law')} className="hover:text-white transition-colors">{__('Trade Law Compliance')}</Link></li>
                        <li><Link href={route('pages.show', { slug: 'about-us' })} className="hover:text-white transition-colors">{__('Company Profile')}</Link></li>
                    </ul>
                </div>

                <div>
                    <h3 className="text-xs font-black text-indigo-400 uppercase tracking-[0.2em] mb-8">{__('Newsletter')}</h3>
                    <p className="text-slate-500 text-xs mb-6 font-medium leading-relaxed">{__('Subscribe to receive updates, access to exclusive deals, and more.')}</p>
                    <form className="relative">
                        <input 
                            type="email" 
                            placeholder="email@example.com" 
                            className="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-sm font-bold focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all placeholder:text-slate-600" 
                        />
                        <button className="absolute right-2 top-2 bottom-2 bg-white text-slate-900 px-5 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-indigo-400 hover:text-white transition-all active:scale-95 shadow-lg">
                            {__('Join')}
                        </button>
                    </form>
                </div>
            </div>

            <div className="max-w-7xl mx-auto px-6 pt-12 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-8 relative z-10">
                <p className="text-slate-500 text-[10px] font-black uppercase tracking-widest">
                    &copy; {currentYear} EC-NEXT. {__('All rights reserved.')}
                </p>
                <div className="flex gap-8">
                    <img src="/img/payment-methods.png" alt="Payments" className="h-6 opacity-30 grayscale invert" />
                </div>
            </div>
        </footer>
    );
}
