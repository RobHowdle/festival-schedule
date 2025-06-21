<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';

const props = defineProps({
    modelValue: {
        type: [String, Number],
        default: '',
    },
    options: {
        type: Array,
        required: true,
    },
    placeholder: {
        type: String,
        default: 'Select an option',
    },

    inputClasses: {
        type: String,
        default: 'w-full px-4 py-3 bg-white/10 backdrop-blur-sm border border-white/30 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-400/50 focus:border-green-400 transition-all duration-200 text-left relative flex items-center justify-between',
    },

    optionsClasses: {
        type: String,
        default: 'absolute z-10 w-full mt-1 bg-white/10 backdrop-blur-xl border border-white/20 rounded-xl shadow-xl py-1 ring-1 ring-black ring-opacity-5 focus:outline-none overflow-hidden',
    },

    optionItemClasses: {
        type: String,
        default: 'text-white cursor-pointer select-none relative py-2 pl-3 pr-9 hover:bg-white/20 transition-colors duration-100',
    },

    error: {
        type: String,
        default: '',
    },
});

const emit = defineEmits(['update:modelValue']);

const isOpen = ref(false);
const selectedOption = computed(() => {
    return props.options.find(option => option.value === props.modelValue) || { label: props.placeholder, value: '' };
});

const toggleDropdown = () => {
    isOpen.value = !isOpen.value;
};

const selectOption = (option) => {
    emit('update:modelValue', option.value);
    isOpen.value = false;
};


const dropdownRef = ref(null);
const handleClickOutside = (event) => {
    if (dropdownRef.value && !dropdownRef.value.contains(event.target)) {
        isOpen.value = false;
    }
};

onMounted(() => {
    document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside);
});

// TODO: Add keyboard navigation (ArrowUp, ArrowDown, Enter, Escape)
// TODO: Add ARIA attributes
</script>

<template>
    <div class="relative" ref="dropdownRef">
        <button
            type="button"
            :class="inputClasses"
            @click="toggleDropdown"
            :aria-expanded="isOpen"
            aria-haspopup="listbox"
        >

            <span :class="{'text-gray-400': !selectedOption.value, 'text-left flex-grow': true}">{{ selectedOption.label }}</span>
            
            <svg class="flex-shrink-0 ml-2 -mr-1 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"> <!-- ADDED flex-shrink-0 ml-2 -mr-1 -->
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
        </button>

        <ul v-if="isOpen" :class="optionsClasses" role="listbox" :aria-labelledby="selectedOption.id">
            <li
                v-for="option in props.options"
                :key="option.value"
                :value="option.value"
                :class="optionItemClasses"
                @click="selectOption(option)"
                role="option"
                :aria-selected="option.value === props.modelValue"
            >
                {{ option.label }}
                <span v-if="option.value === props.modelValue" class="absolute inset-y-0 right-0 flex items-center pr-4">
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.815 3.815L15.352 4.303a.75.75 0 011.052-.143z" clip-rule="evenodd" />
                    </svg>
                </span>
            </li>
        </ul>

        <Transition enter-active-class="duration-200 ease-out" enter-from-class="opacity-0 translate-y-1" enter-to-class="opacity-100 translate-y-0" leave-active-class="duration-150 ease-in" leave-from-class="opacity-100 translate-y-0" leave-to-class="opacity-0 translate-y-1">
            <div v-if="error" class="mt-1 text-sm text-red-400">
                {{ error }}
            </div>
        </Transition>
    </div>
</template>