import React from 'react';
import Header from '@/Components/Shop/Layout/Header';
import Footer from '@/Components/Shop/Layout/Footer';

export default function ShopLayout({ children }) {
    return (
        <div className="min-h-screen flex flex-col">
            <Header />
            <main className="flex-grow">
                {children}
            </main>
            <Footer />
        </div>
    );
}
