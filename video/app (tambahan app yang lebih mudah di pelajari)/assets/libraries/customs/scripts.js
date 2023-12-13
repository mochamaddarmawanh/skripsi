const app = document.getElementById('app')
const upload = document.getElementById('upload')
const uji = document.getElementById('uji')
const loading = document.getElementById('loading')

async function load_models() {
    await faceapi.nets.ssdMobilenetv1.loadFromUri('/sell/face_recognition_2/assets/libraries/face-api.js/weights')
    await faceapi.nets.faceLandmark68Net.loadFromUri('/sell/face_recognition_2/assets/libraries/face-api.js/weights')
    await faceapi.nets.faceRecognitionNet.loadFromUri('/sell/face_recognition_2/assets/libraries/face-api.js/weights')
}

// ----------------------------------------------------------------------------------------------------------------------------- start upload

let file_count = 1

function app_upload() {
    upload.removeAttribute('hidden')
    app.setAttribute('hidden', 'true')
}

function replace_characters() {
    let get_file_name = document.getElementById('file_name')
    let replace = get_file_name.value.trim().replace(/[^a-zA-Z0-9\s]/g, '').replace(/\s+/g, ' ').replace(/^\s|\s$/g, '')
    get_file_name.value = replace
}

function add() {
    file_count++

    const br_1 = document.createElement('br')
    const br_2 = document.createElement('br')

    const new_input = document.createElement('input')
    new_input.type = 'file'
    new_input.name = `file_upload_${file_count}`
    new_input.id = `file_upload_${file_count}`

    const new_delete_button = document.createElement('button')
    new_delete_button.textContent = 'Delete'
    new_delete_button.addEventListener('click', function () {
        br_1.remove()
        br_2.remove()
        new_input.remove()
        new_delete_button.remove()
    })

    upload.appendChild(br_1)
    upload.appendChild(br_2)
    upload.appendChild(new_input)
    upload.appendChild(new_delete_button)
}

function cls() {
    const file_inputs = document.querySelectorAll('input[type="file"]:not([id="select_file"]):not([name="select_file"])')

    file_inputs.forEach(input => {
        const delete_button = input.nextElementSibling;
        if (delete_button) {
            delete_button.remove();
        }
        if (input) {
            input.remove();
        }
    })

    const br_elements = document.querySelectorAll('br')
    let delete_br = false

    br_elements.forEach(br => {
        if (delete_br  && !br.closest('#uji')) {
            br.remove()
        }
        if (br.nextElementSibling && (br.nextElementSibling.id === 'file_name' || br.nextElementSibling.name === 'file_name')) {
            delete_br = true
        }
    })

    const file_upload_1 = document.createElement('input')
    file_upload_1.type = 'file'
    file_upload_1.name = 'file_upload_1'
    file_upload_1.id = 'file_upload_1'
    upload.appendChild(document.createElement('br'))
    upload.appendChild(document.createElement('br'))
    upload.appendChild(document.createElement('br'))
    upload.appendChild(file_upload_1)

    document.getElementById('file_name').value = ''
}

function save() {
    const file_input = document.querySelectorAll('input[type="file"]:not([id="select_file"]):not([name="select_file"])')
    const face_name = document.getElementById('file_name').value.trim()

    let has_selected_file = false

    file_input.forEach(input => {
        if (input.files.length > 0) {
            has_selected_file = true
            return
        }
    })

    if (face_name === '') {
        alert('Nama tidak boleh kosong.')
    } else if (!has_selected_file) {
        alert('Pilih setidaknya satu gambar untuk diunggah.')
    } else {
        loading.style.display = 'block'

        const image_datas = new FormData()

        image_datas.append('file_name', face_name)

        file_input.forEach((input, index) => {
            if (input.files.length > 0) {
                image_datas.append(`file_upload_${index + 1}`, input.files[0])
            }
        })

        fetch('upload.php', {
            method: 'POST',
            body: image_datas,
        }).then(response => response.text()).then(async data => {
            if (data !== 'success') {
                loading.style.display = 'none'

                alert(data)
            } else {
                const compute_datas = new FormData()
                compute_datas.append('face_name', face_name)
                compute_datas.append('descriptors', JSON.stringify(await get_descriptors(file_input)))

                fetch('save.php', {
                    method: 'POST',
                    body: compute_datas
                }).then(response => response.text()).then(data => {
                    loading.style.display = 'none'

                    if (data !== 'success') {
                        alert('Data gagal menjalankan webcam, silahkan ulangi kembali.')
                    } else {
                        alert('Data berhasil di simpan.')
                    }

                    console.log(data)
                }).catch(error => {
                    loading.style.display = 'none'

                    alert('Gagal menjalankan webcam, silahkan ulangi kembali.')
                    console.error('ERROR:', error)
                })
            }
        }).catch(error => {
            loading.style.display = 'none'

            alert('Gagal menjalankan webcam, silahkan ulangi kembali.')
            console.error('ERROR:', error)
        })
    }
}

async function get_descriptors(file_input) {
    await load_models()

    const descriptors = []

    for (let i = 0; i < file_input.length; i++) {
        const input = file_input[i]
        if (input.files && input.files.length > 0) {
            const image = await faceapi.fetchImage(URL.createObjectURL(input.files[0]))
            const detection = await faceapi.detectSingleFace(image).withFaceLandmarks().withFaceDescriptor()
            if (detection) {
                descriptors.push(detection.descriptor)
            }
        }
    }

    return descriptors
}

function app_upload_back() {
    app.removeAttribute('hidden')
    upload.setAttribute('hidden', 'true')
}

// ----------------------------------------------------------------------------------------- end upload

// ---------------------------------------------------------------------------------------------- start recognition

// ------------------------------------------------------------------- start through_webcam

const webcam = document.getElementById('webcam')
const file = document.getElementById('file')

const video_element = document.getElementById('video')
const select_webcam = document.getElementById('select_webcam')

let onWebcam = false
let isRunning = false

function app_uji() {
    uji.removeAttribute('hidden')
    app.setAttribute('hidden', 'true')
}

function uji_upload_back() {
    onWebcam = false
    isRunning = false

    stop_stream()

    app.removeAttribute('hidden')
    uji.setAttribute('hidden', 'true')
}

async function load_labeled_images() {
    try {

        const labeled_face_descriptors = []

        const response = await fetch('faces.php', {
            method: 'POST',
        })

        const face_datas = await response.json()

        for (let i = 0; i < face_datas['faces'].length; i++) {
            const label = face_datas['faces'][i].face_name.replace('_', ' ').toUpperCase()
            const descriptors = face_datas['faces'][i].descriptor

            if (descriptors && descriptors.length > 0) {
                const descriptor = descriptors.map(d => new Float32Array(d))
                labeled_face_descriptors.push(new faceapi.LabeledFaceDescriptors(label, descriptor))
            }
        }

        return labeled_face_descriptors

    } catch (error) {
        console.log('ERROR: ' + error)
        return 'error'
    }
}

function get_available_webcams() {
    return navigator.mediaDevices.enumerateDevices().then(function (devices) {

        const webcams = devices.filter(function (device) {
            return device.kind === 'videoinput'
        })

        return webcams

    }).catch(function (error) {
        console.log('ERROR: ' + error)
        return 'error'
    })
}

async function through_webcam(selected_webcam) {
    if (onWebcam === false) {

        try {

            loading.style.display = 'block'

            webcam.removeAttribute('hidden')
            file.setAttribute('hidden', 'true')

            await load_models()

            const constraints = {
                video: {}

                // video: {
                //     width: { ideal: 1920 },
                //     height: { ideal: 1080 },
                //     frameRate: { ideal: 30 }
                // }
                // video: {
                //     width: { max: 320 },
                //     height: { max: 240 },
                //     frameRate: { max: 10 }
                // }
            }

            if (selected_webcam) {
                constraints.video.deviceId = selected_webcam
            } else {
                constraints.video = true
            }

            const stream = await navigator.mediaDevices.getUserMedia(constraints)

            video_element.srcObject = stream

            const available_webcams = await get_available_webcams()

            if (available_webcams === "error") {
                loading.style.display = 'none'

                stop_stream()

                webcam.setAttribute('hidden', 'true')
                alert('Gagal menjalankan webcam, silahkan ulangi kembali.')
                return
            }

            select_webcam.innerHTML = "<option value='' disabled>Switch Webcam</option>"

            available_webcams.forEach(function (webcam, index) {
                const option = document.createElement('option')

                option.value = webcam.deviceId
                option.text = webcam.label

                if (option.value === selected_webcam) {
                    option.selected = true
                }

                select_webcam.appendChild(option)
            })

            video_element.play().then(async () => {

                const existing_canvas = document.getElementById('canvas_webcam')

                if (existing_canvas) {
                    existing_canvas.remove()
                }

                const canvas = faceapi.createCanvasFromMedia(video_element)

                canvas.id = 'canvas_webcam'

                video_element.parentElement.appendChild(canvas)

                const display_size = { width: video_element.width, height: video_element.height }

                faceapi.matchDimensions(canvas, display_size)

                const labeled_face_descriptors = await load_labeled_images()

                if (labeled_face_descriptors.length === 0) {
                    loading.style.display = 'none'

                    stop_stream()

                    webcam.setAttribute('hidden', 'true')
                    alert('Tidak ada data wajah yang tersimpan.')
                    return
                }

                const face_matcher = new faceapi.FaceMatcher(labeled_face_descriptors, 0.45)

                async function process_frame() {

                    if (!isRunning) return

                    const detections = await faceapi.detectAllFaces(video_element).withFaceLandmarks().withFaceDescriptors()

                    const resized_detections = faceapi.resizeResults(detections, display_size)

                    canvas.getContext('2d').drawImage(video_element, 0, 0, display_size.width, display_size.height)

                    detections.forEach(fd => {
                        if (fd.detection) {
                            const best_match = face_matcher.findBestMatch(fd.descriptor);
                            const accurasy = best_match.label === 'unknown' ? 0 : Math.round((1 - best_match.distance) * 100);
                            const detected_name = best_match.label + ' (' + accurasy + '%)';
                            const landmarks = best_match.landmarks;

                            new faceapi.draw.DrawBox(fd.detection.box, {
                                label: detected_name.replace(/_/g, ' ')
                            }).draw(canvas);

                            if (landmarks) {
                                faceapi.draw.drawFaceLandmarks(canvas, landmarks);
                            }
                        }
                    })

                    requestAnimationFrame(process_frame)

                    select_webcam.removeAttribute('hidden')

                    loading.style.display = 'none'

                }

                onWebcam = true
                isRunning = true

                process_frame()

            })

        } catch (error) {
            onWebcam = false
            isRunning = false

            loading.style.display = 'none'

            stop_stream()

            webcam.setAttribute('hidden', 'true')
            alert('Gagal menjalankan webcam, silahkan ulangi kembali.')
            console.log('ERROR: ' + error)
        }
    }
}

function change_webcam() {
    onWebcam = false
    isRunning = false

    const selected_webcam = document.getElementById('select_webcam').value
    through_webcam(selected_webcam)
}

function stop_stream() {
    isRunning = false

    const media_stream = video.srcObject

    if (media_stream) {
        media_stream.getTracks().forEach(function (track) {
            track.stop()
        })
    }
}

// -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- end through_webcam

// -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- start through_file

function through_file() {
    onWebcam = false
    isRunning = false

    stop_stream()

    file.removeAttribute('hidden')
    webcam.setAttribute('hidden', 'true')
}

function upload_image() {

    const input = document.getElementById('select_file')

    if (input.files && input.files[0]) {

        try {

            loading.style.display = 'block'

            const image_container = document.getElementById('image_uploaded')

            const reader = new FileReader()

            reader.onload = async function (e) {

                const image = document.createElement('img')
                image.src = e.target.result
                image.alt = e.target.result

                image_container.innerHTML = ''

                await load_models()

                const canvas = faceapi.createCanvasFromMedia(image)

                const display_size = { width: image.width, height: image.height }

                faceapi.matchDimensions(canvas, display_size)

                const labeled_face_descriptors = await load_labeled_images()
                const face_matcher = new faceapi.FaceMatcher(labeled_face_descriptors, 0.45)
                const detections = await faceapi.detectAllFaces(image).withFaceLandmarks().withFaceDescriptors()

                canvas.getContext('2d').drawImage(image, 0, 0, display_size.width, display_size.height)

                if (labeled_face_descriptors.length === 0) {
                    loading.style.display = 'none'

                    alert('Tidak ada data wajah yang tersimpan.')
                    return
                }

                if (detections.length === 0) {
                    loading.style.display = 'none'

                    alert("Wajah tidak terdeteksi pada gambar yang diunggah.")
                    return
                }

                detections.forEach(fd => {
                    if (fd.detection) {
                        const best_match = face_matcher.findBestMatch(fd.descriptor)
                        const accurasy = best_match.label === 'unknown' ? 0 : Math.round((1 - best_match.distance) * 100)
                        const detected_name = best_match.label + ' (' + accurasy + '%)'

                        image_container.appendChild(canvas)

                        new faceapi.draw.DrawBox(fd.detection.box, {
                            label: detected_name.replace(/_/g, ' ')
                        }).draw(canvas)
                    }
                });

                loading.style.display = 'none'

            }

            reader.readAsDataURL(input.files[0])

        } catch (error) {
            loading.style.display = 'none'

            alert('Gagal memproses gambar, silahkan ulangi kembali.')
            console.log('ERROR: ' + error)
        }
    }
}

// -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- end through_file

// ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- end recognition