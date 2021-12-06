import 'boxicons'
import '@atom/resources/js/helpers.js'
import '@atom/resources/js/script-loader.js'
import Modal from '@atom/resources/js/components/modal.js'
import InputTags from '@atom/resources/js/components/input-tags.js'
import InputDate from '@atom/resources/js/components/input-date.js'
import InputPhone from '@atom/resources/js/components/input-phone.js'
import InputPicker from '@atom/resources/js/components/input-picker.js'
import InputFile from '@atom/resources/js/components/input-file.js'
import InputFileImage from '@atom/resources/js/components/input-file-image.js'
import InputFileDevice from '@atom/resources/js/components/input-file-device.js'
import InputFileLibrary from '@atom/resources/js/components/input-file-library.js'
import InputFileYoutube from '@atom/resources/js/components/input-file-youtube.js'
import InputRichtext from '@atom/resources/js/components/input-richtext.js'
import NotifyAlert from '@atom/resources/js/components/notify-alert.js'
import NotifyToast from '@atom/resources/js/components/notify-toast.js'
import NotifyConfirm from '@atom/resources/js/components/notify-confirm.js'
import FullscreenLoader from '@atom/resources/js/components/fullscreen-loader.js'
import Tooltip from '@atom/resources/js/directives/tooltip.js'
import Alpine from 'alpinejs'
 
window.Alpine = Alpine
Alpine.data('modal', Modal)
Alpine.data('inputTags', InputTags)
Alpine.data('inputDate', InputDate)
Alpine.data('inputPhone', InputPhone)
Alpine.data('inputPicker', InputPicker)
Alpine.data('inputFile', InputFile)
Alpine.data('inputFileImage', InputFileImage)
Alpine.data('inputFileDevice', InputFileDevice)
Alpine.data('inputFileLibrary', InputFileLibrary)
Alpine.data('inputFileYoutube', InputFileYoutube)
Alpine.data('inputRichtext', InputRichtext)
Alpine.data('notifyAlert', NotifyAlert)
Alpine.data('notifyToast', NotifyToast)
Alpine.data('notifyConfirm', NotifyConfirm)
Alpine.data('fullscreenLoader', FullscreenLoader)
Alpine.directive('tooltip', Tooltip)
Alpine.start()
