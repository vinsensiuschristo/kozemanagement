<x-filament-panels::page>
    <!-- Header Section -->
    <div class="mb-6">
        <div class="bg-gradient-to-r from-primary-500 to-primary-600 dark:from-primary-600 dark:to-primary-700 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold mb-2">
                        Selamat Datang di Koze Management! 🏠
                    </h1>
                    <p class="text-primary-100 dark:text-primary-200">
                        Dashboard modern untuk mengelola sistem kos Anda dengan mudah dan efisien
                    </p>
                </div>
                <div class="hidden md:block">
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4">
                        <div class="text-center">
                            <div class="text-2xl font-bold">{{ now('Asia/Jakarta')->format('H:i') }}</div>
                            <div class="text-sm opacity-90">{{ now('Asia/Jakarta')->format('d M Y') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Widgets Grid -->
    <div class="space-y-6">
        @foreach ($this->getWidgets() as $widget)
            @livewire($widget)
        @endforeach
    </div>

    <!-- Footer Info -->
    <div class="mt-8 text-center text-sm text-gray-500 dark:text-gray-400">
        <p>Dashboard diperbarui secara real-time • Terakhir diperbarui: {{ now()->format('H:i:s') }}</p>
    </div>

    <!-- Custom Styles -->
    <style>
        .fi-wi-stats-overview-stat {
            transition: all 0.3s ease;
        }
        
        .fi-wi-stats-overview-stat:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .fi-wi-chart {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fi-widget {
            animation: fadeInUp 0.6s ease-out;
        }
    </style>
</x-filament-panels::page>
