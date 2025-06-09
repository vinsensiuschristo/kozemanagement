<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center gap-2">
                <x-heroicon-o-bolt class="w-5 h-5 text-primary-500" />
                Quick Actions
            </div>
        </x-slot>
        
        <x-slot name="description">
            Akses cepat ke fitur-fitur utama sistem
        </x-slot>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach($actions as $action)
                <a href="{{ $action['url'] }}" 
                   class="group relative overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-6 hover:shadow-lg transition-all duration-300 hover:scale-105 hover:border-{{ $action['color'] }}-300">
                    
                    <!-- Background Gradient -->
                    <div class="absolute inset-0 bg-gradient-to-br from-{{ $action['color'] }}-50 to-transparent dark:from-{{ $action['color'] }}-900/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    
                    <!-- Content -->
                    <div class="relative z-10">
                        <!-- Icon -->
                        <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-{{ $action['color'] }}-100 dark:bg-{{ $action['color'] }}-900/30 mb-4 group-hover:scale-110 transition-transform duration-300">
                            @svg($action['icon'], 'w-6 h-6 text-' . $action['color'] . '-600 dark:text-' . $action['color'] . '-400')
                        </div>
                        
                        <!-- Title -->
                        <h3 class="font-semibold text-gray-900 dark:text-white mb-2 group-hover:text-{{ $action['color'] }}-600 transition-colors duration-300">
                            {{ $action['label'] }}
                        </h3>
                        
                        <!-- Description -->
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            {{ $action['description'] }}
                        </p>
                        
                        <!-- Arrow Icon -->
                        <div class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <x-heroicon-o-arrow-top-right-on-square class="w-4 h-4 text-{{ $action['color'] }}-500" />
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
