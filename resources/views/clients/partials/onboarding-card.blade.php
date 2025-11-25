
<style>
/* Mobile-specific adjustments */
@media (max-width: 640px) {
    .onboarding-card {
        width: 95% !important;
        height: auto !important;
        min-height: 300px;
        padding: 1rem !important;
    }

    .onboarding-title, .categories-title, .cart-title, .checkout-title, .order-confirmed-title {
        font-size: 1.25rem !important;
        line-height: 1.5rem !important;
    }

    .onboarding-subtitle, .categories-subtitle, .cart-subtitle, .checkout-subtitle {
        font-size: 0.875rem !important;
        line-height: 1.25rem !important;
        margin-top: 0.5rem !important;
    }

    .product-name {
        font-size: 0.875rem !important;
        line-height: 1.25rem !important;
    }

    .product-price {
        font-size: 0.75rem !important;
        line-height: 1rem !important;
    }

    .product-card {
        width: 5rem !important;
        height: 5rem !important;
    }

    .product-card img {
        width: 2rem !important;
        height: 2rem !important;
        margin-bottom: 0.5rem !important;
    }

    .product-cards-row {
        flex-direction: column !important;
        align-items: center;
        gap: 1rem !important;
    }

    .order-summary, .product-item, .ssl-info {
        width: 100% !important;
        max-width: 100% !important;
    }

    .product-item {
        flex-direction: column !important;
        align-items: flex-start !important;
        gap: 0.5rem !important;
    }

    .product-item > div {
        width: 100% !important;
        max-width: 100% !important;
    }

    .quantity-control {
        width: auto !important;
        padding: 0.25rem 0.5rem !important;
    }

    .top-center-card {
        width: 5rem !important;
        height: 4rem !important;
    }

    .top-center-card img {
        width: 1.5rem !important;
        height: 1.5rem !important;
    }

    .order-confirmed-card {
        width: 8rem !important;
        height: 6rem !important;
    }

    .order-confirmed-card img {
        width: 2rem !important;
        height: 2rem !important;
    }

    .buttons {
        flex-direction: row !important;
        gap: 0.5rem !important;
        padding: 0 0.5rem !important;
    }

    .buttons button {
        width: 5rem !important;
        height: 2rem !important;
        padding: 0.25rem 0.5rem !important;
        font-size: 0.875rem !important;
        line-height: 1rem !important;
    }

    .tab-indicators {
        gap: 0.5rem !important;
    }

    .tab-indicator {
        width: 1.5rem !important;
        height: 0.25rem !important;
    }

    .get-started-btn {
        font-size: 10px !important;
        padding: 0.5rem 1rem !important;
    }
}

/* Common styles */
.onboarding-title, .categories-title, .cart-title, .checkout-title, .order-confirmed-title {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-weight: 700;
    text-align: center;
    color: #ffffff;
}

.onboarding-subtitle, .categories-subtitle, .cart-subtitle, .checkout-subtitle {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-weight: 400;
    text-align: center;
    color: #D0D0D0;
    margin-top: 16px;
}

.product-name {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-weight: 600;
    color: #ffffff;
}

.product-price {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-weight: 400;
    color: #D0D0D0;
}
</style>

    <div x-data="{ showCard: true, currentTab: 1, totalTabs: 6 }" x-show="showCard">
    <!-- Overlay -->
    <div
        class="fixed z-50 rounded-[20px] shadow-xl transition-all duration-300 p-6 onboarding-card"
        :style="currentTab === 6
            ? 'width: 567px; height: 347px; top: 50%; left: 50%; transform: translate(-50%, -50%); background: rgba(255,255,255,0.2); backdrop-filter: blur(30px) saturate(180%); border: 1px solid rgba(255,255,255,0.25); border-radius: 20px;'
            : currentTab === 5
            ? 'width: 471px; height: 563px; top: 50%; left: 50%; transform: translate(-50%, -50%); background: rgba(255,255,255,0.2); backdrop-filter: blur(30px) saturate(180%); border: 1px solid rgba(255,255,255,0.25); border-radius: 20px;'
            : currentTab === 4
            ? 'width: 471px; height: 525px; top: 50%; left: 50%; transform: translate(-50%, -50%); background: rgba(255,255,255,0.2); backdrop-filter: blur(30px) saturate(180%); border: 1px solid rgba(255,255,255,0.25); border-radius: 20px;'
            : 'width: 470px; height: 426px; top: 50%; left: 50%; transform: translate(-50%, -50%); background: rgba(255,255,255,0.2); backdrop-filter: blur(30px) saturate(180%); border: 1px solid rgba(255,255,255,0.25); border-radius: 20px;'">

        <!-- Tab Indicators -->
        <div class="flex justify-center gap-4 w-full mt-4 tab-indicators">
            <template x-for="i in totalTabs" :key="i">
                <div @click="currentTab = i"
                     :class="i === currentTab ? 'bg-blue-600' : 'bg-white'"
                     class="w-8 h-2 rounded-full cursor-pointer transition-all duration-300 tab-indicator"></div>
            </template>
        </div>
            <!-- First Tab -->
            <div x-show="currentTab === 1" x-transition class="flex flex-col justify-between items-center h-full p-6">
                <div class="mt-4 flex justify-center w-full">
                    <img src="/storage/onboarding/Dashboard Icon.png" alt="Dashboard Icon" class="w-24 h-24 object-contain">
                </div>
                <div class="text-center mt-6">
                    <h2 class="onboarding-title">
                        Welcome to Your <br>Dashboard
                    </h2>
                    <style>
                        .onboarding-title {
                            font-family: 'Plus Jakarta Sans', sans-serif;
                            font-weight: 700;
                            font-style: normal;
                            font-size: 24px;
                            line-height: 35px;
                            letter-spacing: 0;
                            text-align: center;
                            color: #ffffff;
                        }
                    </style>
                    <p class="onboarding-subtitle">
                        Manage products, track orders, and complete sales efficiently with ease.
                    </p>
                    <style>
                        .onboarding-subtitle {
                            font-family: 'Plus Jakarta Sans', sans-serif;
                            font-weight: 400;
                            font-style: normal;
                            font-size: 16px;
                            line-height: 25.2px;
                            letter-spacing: 0;
                            text-align: center;
                            color: #D0D0D0;
                            margin-top: 16px;
                        }
                    </style>
                </div>
                <div class="flex justify-between w-full mt-auto mb-6 px-4 buttons">
                    <button @click="finishOnboarding()" class="font-medium text-[15px] text-gray-700 hover:underline">
                        Skip
                    </button>
                    <button 
    @click="currentTab = 2" 
    class="get-started-btn flex items-center justify-center gap-2 px-3 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-all duration-300"
>
    Get Started
    <i class="fa-solid fa-arrow-right"></i>
</button>



                </div>
            </div>

            <!-- Second Tab -->
            <div x-show="currentTab === 2" x-transition class="flex flex-col items-center h-full p-6 space-y-6">
                <div class="flex gap-6 justify-center w-full product-cards-row">
                    <div class="w-24 h-22 p-2 rounded-[16px] shadow-lg flex flex-col items-center justify-center overflow-hidden transition-transform duration-300 hover:scale-105 product-card" style="background: linear-gradient(225deg, #4F46E5 15%, #7C3AED 85%); border-radius: 12px;">
                        <img src="/storage/onboarding/Package.png" alt="Men's" class="w-12 h-12 mb-4 p-2">
                        <span class="text-white font-semibold text-sm text-center">Men's</span>
                    </div>
                    <div class="w-24 h-22 p-2 rounded-[16px] shadow-lg flex flex-col items-center justify-center overflow-hidden transition-transform duration-300 hover:scale-105 product-card" style="background: linear-gradient(225deg, #10B981 15%, #059669 85%); border-radius: 12px;">
                        <img src="/storage/onboarding/Heart.png" class="w-12 h-12 mb-4 p-2" alt="Women's">
                        <span class="text-white font-semibold text-sm text-center">Women's</span>
                    </div>
                </div>
                <div class="text-center mt-4">
                    <h2 class="categories-title">Browse Product Categories</h2>
                    <p class="categories-subtitle">Select Men's or Women's footwear to explore and manage products.</p>
                    <style>
                        .categories-title {
                            font-family: 'Plus Jakarta Sans', sans-serif;
                            font-weight: 700;
                            font-style: normal;
                            font-size: 24px;
                            line-height: 38.4px;
                            letter-spacing: 0;
                            text-align: left;
                            color: #111111;
                        }
                        .categories-subtitle {
                            font-family: 'Plus Jakarta Sans', sans-serif;
                            font-weight: 400;
                            font-style: normal;
                            font-size: 16px;
                            line-height: 25.2px;
                            letter-spacing: 0;
                            text-align: left;
                            color: #6B7280;
                            margin-top: 8px;
                        }
                    </style>
                </div>
                <div class="flex justify-between w-full mt-auto px-4 buttons">
                    <button @click="currentTab = 1" class="flex items-center justify-center gap-2 shadow-sm transition hover:shadow-md" style="width: 100px; height: 38px; border-radius: 10px; padding-left: 24px; padding-right: 24px; background: #F3F4F6; font-family: 'Plus Jakarta Sans'; font-weight: 600; font-style: SemiBold; font-size: 16px; line-height: 19.2px; color: #1F2937; letter-spacing: 0%;">
                        <i class="fa-solid fa-arrow-left" style="border: 1px solid #D1D5DB; padding: 2px; border-radius: 50%;"></i> Back
                    </button>
                    <button @click="currentTab = 3" class="flex items-center justify-center gap-2 shadow-sm transition hover:shadow-md" style="width: 100px; height: 38px; border-radius: 10px; padding-left: 32px; padding-right: 32px; background: #4F46E5; color: #FFFFFF; font-family: 'Plus Jakarta Sans'; font-weight: 600; font-style: SemiBold; font-size: 16px; line-height: 19.2px; letter-spacing: 0%;">
                        Next <i class="fa-solid fa-arrow-right" style="border: 1px solid #FFFFFF; padding: 2px; border-radius: 50%;"></i>
                    </button>
                </div>
            </div>

            <!-- Third Tab -->
            <div x-show="currentTab === 3" x-transition class="flex flex-col items-center h-full space-y-4 p-6">
                <div class="w-24 h-22 p-2 rounded-[16px] shadow-lg flex flex-col items-center justify-center overflow-hidden transition-transform duration-300 hover:scale-105 product-card" style="background: linear-gradient(225deg, #10B981 15%, #059669 85%); border-radius: 12px;">
                    <img src="/storage/onboarding/Heart.png" class="w-12 h-12 mb-4 p-2" alt="Women's">
                    <span class="text-white font-semibold text-sm text-center">Women's</span>
                </div>
                <h2 class="categories-title">Browse Product Categories</h2>
                <p class="categories-subtitle">Select Men's or Women's footwear to explore and manage products.</p>
                <style>
                    .categories-title {
                        font-family: 'Plus Jakarta Sans', sans-serif;
                        font-weight: 700;
                        font-style: normal;
                        font-size: 24px;
                        line-height: 38.4px;
                        letter-spacing: 0;
                        text-align: center;
                        color: #ffffff;
                    }
                    .categories-subtitle {
                        font-family: 'Plus Jakarta Sans', sans-serif;
                        font-weight: 400;
                        font-style: normal;
                        font-size: 16px;
                        line-height: 25.2px;
                        letter-spacing: 0;
                        text-align: center;
                        color: #D0D0D0;
                    }
                </style>
                <div class="flex justify-between w-full mt-auto px-4 buttons">
                    <button @click="currentTab = 2" class="flex items-center justify-center gap-2" style="width: 100px; height: 38px; border-radius: 10px; padding-left: 24px; padding-right: 24px; background: #F3F4F6; font-family: 'Plus Jakarta Sans'; font-weight: 600; font-style: SemiBold; font-size: 16px; line-height: 19.2px; letter-spacing: 0%; opacity: 1;">
                        <i class="fa-solid fa-arrow-left" style="border: 1px solid #FFFFFF; padding: 2px;"></i> Back
                    </button>
                    <button @click="currentTab = 4" class="flex items-center justify-center gap-2" style="width: 100px; height: 38px; border-radius: 10px; padding-left: 32px; padding-right: 32px; background: #4F46E5; color: #FFFFFF; font-family: 'Plus Jakarta Sans'; font-weight: 600; font-style: SemiBold; font-size: 16px; line-height: 19.2px; letter-spacing: 0%; opacity: 1;">
                        Next <i class="fa-solid fa-arrow-right" style="border: 0px solid #FFFFFF; padding: 2px;"></i>
                    </button>
                </div>
            </div>

            <!-- Fourth Tab -->
            <div x-show="currentTab === 4" x-transition class="flex flex-col items-center space-y-4 h-full p-6">
                <div class="w-[98px] h-[75px] rounded-[7px] bg-[#F8FAFC] flex items-center justify-center mx-auto top-center-card" style="border-radius: 7px;">
                    <img src="/storage/onboarding/Shopping Cart.png" class="w-8 h-8" alt="Cart">
                </div>
                <h2 class="cart-title">Manage Your Cart</h2>
                <p class="cart-subtitle">Update quantities, remove products, and view a real-time order summary.</p>
                <style>
                    .cart-title {
                        font-family: 'Plus Jakarta Sans', sans-serif;
                        font-weight: 700;
                        font-style: normal;
                        font-size: 24px;
                        line-height: 38.4px;
                        letter-spacing: 0;
                        text-align: center;
                        color: #ffffff;
                        margin-top: 8px;
                    }
                    .cart-subtitle {
                        font-family: 'Plus Jakarta Sans', sans-serif;
                        font-weight: 400;
                        font-style: normal;
                        font-size: 16px;
                        line-height: 25.2px;
                        letter-spacing: 0;
                        text-align: center;
                        color: #D0D0D0;
                        margin-bottom: 16px;
                    }
                </style>
                <div class="flex flex-col items-center justify-center rounded-[12px] shadow-md order-summary" style="width: 415px; background: #FFFFFF; padding: 8px; border-radius: 12px;">
                    <div class="flex flex-col gap-2 w-full rounded-[8px]" style="background: #E8F5E8; padding: 18px; border-radius: 12px;">
                        <span class="font-semibold text-[12px] text-[#059669]">Order Summary</span>
                        <div class="flex justify-between text-gray-700 text-[12px]">
                            <span>Subtotal :</span>
                            <span>₹240.00</span>
                        </div>
                        <div class="flex justify-between text-gray-700 text-[12px] font-semibold">
                            <span>Total :</span>
                            <span>₹283.20</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-between w-[415px] mt-4 gap-4 product-item">
                    <div class="w-[100px] h-[80px] bg-white rounded-[8px] shadow-md flex items-center justify-center flex-shrink-0">
                        <img src="/storage/onboarding/Shopping Cart.png" class="w-6 h-6" alt="Cart">
                    </div>
                    <div class="product-info flex flex-col flex-1 mx-4">
                        <span class="product-name">High Heels (Red)</span>
                        <span class="product-price">₹120.00</span>
                    </div>
                    <style>
                        .product-name {
                            font-family: 'Plus Jakarta Sans', sans-serif;
                            font-weight: 600;
                            font-style: normal;
                            font-size: 16px;
                            line-height: 25.2px;
                            letter-spacing: 0;
                            color: #ffffff;
                        }
                        .product-price {
                            font-family: 'Plus Jakarta Sans', sans-serif;
                            font-weight: 400;
                            font-style: normal;
                            font-size: 14px;
                            line-height: 22px;
                            letter-spacing: 0;
                            color: #D0D0D0;
                        }
                    </style>
                    <div class="flex items-center gap-2 border rounded-md px-3 py-1 bg-white shadow-md flex-shrink-0 quantity-control">
                        <button class="text-gray-600 font-bold">-</button>
                        <span class="text-gray-800">2</span>
                        <button class="text-gray-600 font-bold">+</button>
                    </div>
                </div>
                <div class="flex justify-between w-full mt-auto px-4 buttons">
                    <button @click="currentTab = 3" class="flex items-center justify-center gap-2 shadow-sm transition hover:shadow-md" style="width: 100px; height: 38px; border-radius: 10px; padding-left: 24px; padding-right: 24px; background: #F3F4F6; font-family: 'Plus Jakarta Sans'; font-weight: 600; font-style: SemiBold; font-size: 16px; line-height: 19.2px; color: #1F2937; letter-spacing: 0%;">
                        <i class="fa-solid fa-arrow-left" style="border: 1px solid #D1D5DB; padding: 2px; border-radius: 50%;"></i> Back
                    </button>
                    <button @click="currentTab = 5" class="flex items-center justify-center gap-2" style="width: 100px; height: 38px; border-radius: 10px; padding-left: 32px; padding-right: 32px; background: #4F46E5; color: #FFFFFF; font-family: 'Plus Jakarta Sans'; font-weight: 600; font-style: SemiBold; font-size: 16px; line-height: 19.2px; letter-spacing: 0%; opacity: 1;">
                        Next <i class="fa-solid fa-arrow-right" style="border: 0px solid #FFFFFF; padding: 2px;"></i>
                    </button>
                </div>
            </div>

            <!-- Fifth Tab -->
            <div x-show="currentTab === 5" x-transition class="flex flex-col items-center space-y-4 h-full p-6">
                <div class="w-[98px] h-[75px] rounded-[7px] bg-white flex items-center justify-center mx-auto shadow-md top-center-card">
                    <img src="/storage/onboarding/Credit Card.png" class="w-[38px] h-[38px]" alt="Credit Card">
                </div>
                <h2 class="checkout-title">Fast & Secure Checkout</h2>
                <p class="checkout-subtitle">Enter shipping details, choose a payment method, and confirm your order with one click.</p>
                <style>
                    .checkout-title {
                        font-family: 'Plus Jakarta Sans', sans-serif;
                        font-weight: 700;
                        font-style: normal;
                        font-size: 24px;
                        line-height: 38.4px;
                        letter-spacing: 0;
                        text-align: center;
                        color: #ffffff;
                        margin-top: 8px;
                    }
                    .checkout-subtitle {
                        font-family: 'Plus Jakarta Sans', sans-serif;
                        font-weight: 400;
                        font-style: normal;
                        font-size: 16px;
                        line-height: 25.2px;
                        letter-spacing: 0;
                        text-align: center;
                        color: #D0D0D0;
                    }
                </style>
                <div class="flex flex-col w-full mt-4">
                    <span class="text-gray-700 text-sm font-medium mb-1">Shipping Information</span>
                    <div class="w-full h-10 bg-white rounded-[8px] border border-gray-300 flex items-center px-3" style="padding: 12px; background: #FFFFFF; border: 1px solid #E2E8F0;">
                        <span class="text-gray-400 text-sm">Enter shipping details</span>
                    </div>
                </div>
                <div class="flex flex-col w-full mt-4">
                    <span class="text-gray-700 text-sm font-medium mb-1">Payment Method</span>
                    <div class="w-full h-10 bg-white rounded-[8px] border border-gray-300 flex items-center px-3 gap-3" style="padding: 12px; background: #FFFFFF; border: 1px solid #E2E8F0;">
                        <img src="/storage/onboarding/Banknote.png" class="w-5 h-5" alt="Cash">
                        <span class="text-gray-700 text-sm flex-1">Cash on Delivery</span>
                        <i class="fa-solid fa-check text-green-600"></i>
                    </div>
                </div>
                <div class="w-[440px] h-[40px] bg-[#E8F5E8] rounded-[8px] flex items-center justify-center gap-2 px-3 ssl-info">
                    <img src="/storage/onboarding/Lock.png" class="w-5 h-5" alt="Lock">
                    <span class="text-[#059669] text-sm font-semibold">256-bit SSL Encryption</span>
                </div>
                <div class="flex justify-between w-full mt-auto px-4 buttons">
                    <button @click="currentTab = 4" class="flex items-center justify-center gap-2" style="width: 100px; height: 38px; border-radius: 10px; padding-left: 24px; padding-right: 24px; background: #F3F4F6; font-family: 'Plus Jakarta Sans'; font-weight: 600; font-style: SemiBold; font-size: 16px; line-height: 19.2px; letter-spacing: 0%; opacity: 1;">
                         <i class="fa-solid fa-arrow-left" style="border: 1px solid #FFFFFF; padding: 2px;"></i>Back
                    </button>
                    <button @click="currentTab = 6" class="flex items-center justify-center gap-2" style="width: 100px; height: 38px; border-radius: 10px; padding-left: 32px; padding-right: 32px; background: #4F46E5; color: #FFFFFF; font-family: 'Plus Jakarta Sans'; font-weight: 600; font-style: SemiBold; font-size: 16px; line-height: 19.2px; letter-spacing: 0%; opacity: 1;">
                        Next <i class="fa-solid fa-arrow-right" style="border: 0px solid #FFFFFF; padding: 2px;"></i>
                    </button>
                </div>
            </div>

            <!-- Sixth Tab -->
            <div x-show="currentTab === 6" x-transition class="flex flex-col items-center space-y-4 h-full p-6">
                <div class="w-[250px] h-[180px] bg-[#FEF3F2] rounded-[12px] flex items-center justify-center shadow-md mb-4 order-confirmed-card" style="border: 1px solid #FECACA;">
                    <img src="/storage/onboarding/Truck.png" class="w-12 h-12" alt="Truck">
                </div>
                <h2 class="order-confirmed-title">Order Confirmed</h2>
                <style>
                    .order-confirmed-title {
                        font-family: 'Plus Jakarta Sans', sans-serif;
                        font-weight: 700;
                        font-style: normal;
                        font-size: 24px;
                        line-height: 35px;
                        letter-spacing: 0;
                        text-align: center;
                        color: #ffffff;
                        margin-bottom: 24px;
                    }
                </style>
                <div class="flex justify-between w-full px-4 mt-auto buttons">
                    <button @click="currentTab = 5" class="flex items-center justify-center gap-2" style="width: 100px; height: 38px; border-radius: 10px; padding-left: 24px; padding-right: 24px; background: #F3F4F6; font-family: 'Plus Jakarta Sans'; font-weight: 600; font-style: SemiBold; font-size: 16px; line-height: 19.2px; letter-spacing: 0%; opacity: 1;">
                        <i class="fa-solid fa-arrow-left" style="border: 1px solid #FFFFFF; padding: 2px;"></i> Back
                    </button>
                    <button @click="finishOnboarding()" class="flex items-center justify-center gap-2" style="width: 100px; height: 38px; border-radius: 10px; padding-left: 32px; padding-right: 32px; background: #4F46E5; color: #FFFFFF; font-family: 'Plus Jakarta Sans'; font-weight: 600; font-style: SemiBold; font-size: 16px; line-height: 19.2px; letter-spacing: 0%; opacity: 1;">
                        Done
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
function finishOnboarding() {
    fetch("/onboarding/seen", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')?.content || ""
        }
    })
    .then(() => {
        const card = document.querySelector('.onboarding-card');
        const overlay = document.querySelector('.overlay');
        if (card) card.remove();
        if (overlay) overlay.remove();
    });
}

document.addEventListener('alpine:init', () => {
    console.log('Alpine.js is initialized');
});
</script>