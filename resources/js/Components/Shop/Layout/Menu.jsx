import React from 'react';
import { Link } from '@inertiajs/react';
import { useTranslation } from '@/Helpers/translations';

export default function Menu({ className = "" }) {
    const { __ } = useTranslation();
    
    const menuItems = [
        { name: __('New Arrivals'), href: route('home') },
        { name: __('Shop All'), href: '#' },
        { name: __('Campaigns'), href: '#' },
        { name: __('Contact'), href: route('inquiry.index') },
    ];

    return (
        <ul className={`flex items-center gap-8 ${className}`}>
            {menuItems.map((item, idx) => (
                <li key={idx}>
                    <Link 
                        href={item.href} 
                        className="text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 hover:text-indigo-600 transition-colors"
                    >
                        {item.name}
                    </Link>
                </li>
            ))}
        </ul>
    );
}
