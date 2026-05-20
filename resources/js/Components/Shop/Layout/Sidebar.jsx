import React from 'react';
import { Link } from '@inertiajs/react';
import { useTranslation } from '@/Helpers/translations';

export default function Sidebar({ children, title }) {
    const { __ } = useTranslation();

    return (
        <aside className="w-full lg:w-80 flex-shrink-0">
            <div className="bg-white rounded-[3rem] p-10 shadow-xl shadow-slate-200/50 border border-white sticky top-24">
                {title && (
                    <div className="mb-10">
                        <h3 className="text-xs font-black text-slate-400 border-b border-slate-100 pb-6 mb-8 uppercase tracking-[0.2em]">
                            {title}
                        </h3>
                    </div>
                )}
                
                <div className="space-y-12">
                    {children}
                </div>

                <div className="mt-16 pt-10 border-t border-slate-50">
                    <div className="bg-slate-900 rounded-[2rem] p-8 text-white relative overflow-hidden group">
                        <div className="absolute top-0 right-0 w-32 h-32 bg-indigo-500/10 rounded-full -mr-16 -mt-16 blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
                        <h4 className="text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-4">{__('VIP Status')}</h4>
                        <p className="text-sm font-black mb-1">Standard Member</p>
                        <div className="mt-6 flex items-center justify-between">
                            <span className="text-[9px] font-bold text-slate-500 uppercase tracking-widest">Next Tier</span>
                            <span className="text-[9px] font-bold text-indigo-400">¥12,400 to Gold</span>
                        </div>
                        <div className="h-1 bg-white/5 rounded-full mt-2 overflow-hidden">
                            <div className="h-full bg-indigo-500 w-[60%] rounded-full shadow-lg shadow-indigo-500/50"></div>
                        </div>
                    </div>
                </div>
            </div>
        </aside>
    );
}
