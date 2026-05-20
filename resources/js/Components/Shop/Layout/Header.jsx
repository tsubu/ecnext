import React from 'react';
import { Link } from '@inertiajs/react';
import { useTranslation } from '@/Helpers/translations';

export default function Header() {
    const { __ } = useTranslation();
    
    return (
        <nav className="fixed top-0 w-full z-50 bg-white/80 backdrop-blur-xl border-b border-slate-100 px-6 py-4 flex justify-between items-center transition-all duration-300">
            <Link href={route('home')} className="text-xl font-black tracking-tighter uppercase group flex items-center gap-2">
                <div className="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center text-white text-sm shadow-lg shadow-indigo-600/20 group-hover:scale-105 transition-transform">N</div>
                <span className="group-hover:text-indigo-600 transition-colors">EC-NEXT</span>
            </Link>
            
            <div className="flex items-center gap-8 text-sm font-bold">
                <div className="hidden md:flex items-center gap-8 uppercase tracking-widest text-[10px]">
                    <Link href={route('home')} className="hover:text-indigo-600 transition-colors">{__('New Arrivals')}</Link>
                    <Link href="#" className="hover:text-indigo-600 transition-colors">{__('Shop All')}</Link>
                    <Link href={route('inquiry.index')} className="hover:text-indigo-600 transition-colors">{__('Contact')}</Link>
                </div>
                
                <div className="flex items-center gap-3 ml-4">
                    <Link href={route('cart.index')} className="p-2 relative hover:bg-slate-100 rounded-full transition-all group">
                        <svg className="w-5 h-5 text-slate-700 group-hover:text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    </Link>
                    
                    <div className="h-4 w-px bg-slate-200 mx-2"></div>
                    
                    <Link href={route('login')} className="text-xs font-black uppercase tracking-widest text-slate-500 hover:text-slate-900 transition-colors">{__('Sign In')}</Link>
                    <Link href={route('register')} className="bg-slate-900 text-white px-6 py-2.5 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-slate-800 transition-all shadow-xl shadow-slate-900/10 active:scale-95">
                        {__('Join Free')}
                    </Link>
                </div>
            </div>
        </nav>
    );
}
