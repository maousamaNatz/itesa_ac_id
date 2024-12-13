<div x-data="notifications"
     @notification.window="add($event.detail)"
     class="fixed top-4 right-4 z-50 space-y-4" style="z-index: 99999">
    <template x-for="notification in notifications" :key="notification.id">
        <div x-show="notification"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform translate-x-8"
             x-transition:enter-end="opacity-100 transform translate-x-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform translate-x-0"
             x-transition:leave-end="opacity-0 transform translate-x-8"
             :class="{
                'bg-green-500': notification.type === 'success',
                'bg-red-500': notification.type === 'error',
                'bg-blue-500': notification.type === 'info'
             }"
             class="rounded-lg p-4 text-white shadow-lg flex items-center space-x-4">
            <div class="flex-1">
                <h3 x-text="notification.title" class="font-bold"></h3>
                <p x-text="notification.message" class="text-sm"></p>
            </div>
            <button @click="remove(notification.id)" class="text-white hover:text-gray-200">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </template>
</div>
