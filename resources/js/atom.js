import './helpers.js'
import './script-loader.js'
import FullscreenLoader from './components/fullscreen-loader'

import ImageInput from './components/input/image'
import RichtextInput from './components/input/richtext'

import Uploader from './uploader/uploader'
import UploaderDevice from './uploader/uploader-device'
import UploaderImage from './uploader/uploader-image'
import UploaderLibrary from './uploader/uploader-library'
import UploaderYoutube from './uploader/uploader-youtube'

Alpine.data('fullscreenLoader', FullscreenLoader)
Alpine.data('imageInput', ImageInput)
Alpine.data('richtextInput', RichtextInput)
Alpine.data('uploader', Uploader)
Alpine.data('uploaderDevice', UploaderDevice)
Alpine.data('uploaderImage', UploaderImage)
Alpine.data('uploaderLibrary', UploaderLibrary)
Alpine.data('uploaderYoutube', UploaderYoutube)

