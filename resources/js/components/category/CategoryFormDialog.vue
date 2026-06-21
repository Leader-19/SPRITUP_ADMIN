<script setup lang="ts">
import { ref, computed, watch } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
  DialogTrigger,
} from '@/components/ui/dialog'
import Button from '@/components/ui/button/Button.vue'
import Input from '@/components/ui/input/Input.vue'
import Label from '@/components/ui/label/Label.vue'
import Textarea from '@/components/ui/textarea/Textarea.vue'
import { X } from 'lucide-vue-next'

interface Category {
  id?: number
  title: string
  description?: string | null
}

const props = defineProps<{
  category?: Category | null
  open?: boolean
  mode?: 'create' | 'edit'
}>()

const emit = defineEmits<{
  'update:open': [value: boolean]
  'success': [category: any]
  'close': []
}>()

const isOpen = computed({
  get: () => props.open ?? false,
  set: (value) => emit('update:open', value)
})

const form = useForm({
  title: props.category?.title || '',
  description: props.category?.description || '',
})

const isEditMode = computed(() => props.mode === 'edit')

const submit = () => {
  if (isEditMode.value && props.category?.id) {
    form.put(
      route('categories.update', props.category.id),
      {
        onSuccess: () => {
          isOpen.value = false
          form.reset()
        },
        onError: () => {
          // Errors are already handled by form
        }
      }
    )
  } else {
    form.post(
      route('categories.store'),
      {
        forceFormData: true,
        onSuccess: () => {
          isOpen.value = false
          form.reset()
        },
        onError: () => {
          // Errors are already handled by form
        }
      }
    )
  }
}

watch(() => props.category, (newCategory) => {
  if (newCategory) {
    form.title = newCategory.title || ''
    form.description = newCategory.description || ''
  } else {
    form.reset()
  }
}, { deep: true })

const closeDialog = () => {
  isOpen.value = false
  form.reset()
  form.clearErrors()
  emit('close')
}
</script>