import React from 'react';

export default function ProductCard({ product }) {
    // Get the default variant or first one for price display
    const variant = product.default_variant || (product.variants && product.variants[0]);
    
    return (
        <div className="group cursor-pointer">
            <div className="relative overflow-hidden aspect-square bg-slate-100 rounded-2xl mb-4">
                {/* Fallback pattern if no image */}
                <div className="absolute inset-0 flex items-center justify-center text-slate-300">
                    <svg className="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                {/* Product Image (assuming a path exists or using placeholder) */}
                <img 
                    src={product.images?.[0]?.file_path || "https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&q=80&w=800"} 
                    alt={product.name}
                    className="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                />
                <div className="absolute top-4 left-4">
                    {product.tags?.map(tag => (
                        <span key={tag.id} className="bg-white/90 backdrop-blur px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider text-slate-900 shadow-sm">
                            {tag.name}
                        </span>
                    ))}
                </div>
            </div>
            
            <h3 className="font-semibold text-slate-900 group-hover:text-indigo-600 transition-colors mb-1">
                {product.name}
            </h3>
            <p className="text-sm text-slate-500 mb-2 truncate">{product.short_description}</p>
            <div className="flex items-center justify-between">
                <span className="text-lg font-bold text-slate-900">
                    ¥{variant?.price ? parseInt(variant.price).toLocaleString() : '0'}
                </span>
                <span className="text-xs font-medium text-slate-400">
                    {variant?.option1_value ? `${variant.option1_name}: ${variant.option1_value}` : ''}
                </span>
            </div>
        </div>
    );
}
