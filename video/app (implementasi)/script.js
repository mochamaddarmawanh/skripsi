async function start_face_api_js() {
    await faceapi.nets.ssdMobilenetv1.loadFromUri('/project/tutorial/face-api-js/face-api.js/weights')
    await faceapi.nets.faceLandmark68Net.loadFromUri('/project/tutorial/face-api-js/face-api.js/weights')
    await faceapi.nets.faceRecognitionNet.loadFromUri('/project/tutorial/face-api-js/face-api.js/weights')

    const tobey = document.getElementById('tobey')
    const tobey_2 = document.getElementById('tobey_2')
    const ronaldo = document.getElementById('ronaldo')
    const ronaldo_2 = document.getElementById('ronaldo_2')

    const img_target = document.getElementById('img_target')

    const detection_tobey = await faceapi.detectSingleFace(tobey).withFaceLandmarks().withFaceDescriptor()
    const detection_tobey_2 = await faceapi.detectSingleFace(tobey_2).withFaceLandmarks().withFaceDescriptor()
    const detection_ronaldo = await faceapi.detectSingleFace(ronaldo).withFaceLandmarks().withFaceDescriptor()
    const detection_ronaldo_2 = await faceapi.detectSingleFace(ronaldo_2).withFaceLandmarks().withFaceDescriptor()

    const labeledDescriptors = [
        new faceapi.LabeledFaceDescriptors(
            'Pater Parker',
            [detection_tobey.descriptor, detection_tobey_2.descriptor]
        ),
        new faceapi.LabeledFaceDescriptors(
            'Cristiano Ronaldo',
            [detection_ronaldo.descriptor, detection_ronaldo_2.descriptor]
        )
    ]

    // const displaySize = { width: tobey.width, height: tobey.height }

    // const canvas = document.getElementById('canvas')

    // faceapi.matchDimensions(canvas, displaySize)

    // const resizedDetections = faceapi.resizeResults(detections, displaySize)

    // canvas.getContext('2d').drawImage(tobey, 0, 0)

    // faceapi.draw.drawDetections(canvas, resizedDetections)

    const results = await faceapi.detectAllFaces(img_target).withFaceLandmarks().withFaceDescriptors()

    if (!results.length) {
        alert('wajah tidak terdeteksi')
        return
    }

    const faceMatcher = new faceapi.FaceMatcher(labeledDescriptors)

    const displaySize = { width: img_target.width, height: img_target.height }

    const canvas = document.getElementById('canvas')

    faceapi.matchDimensions(canvas, displaySize)

    // const resizedDetections = faceapi.resizeResults(results, displaySize)

    canvas.getContext('2d').drawImage(img_target, 0, 0)

    // faceapi.draw.drawDetections(canvas, resizedDetections)

    results.forEach(fd => {
        const bestMatch = faceMatcher.findBestMatch(fd.descriptor)

        new faceapi.draw.DrawBox(fd.detection.box, {
            label: bestMatch.label
        }).draw(canvas)
    })
}

start_face_api_js()