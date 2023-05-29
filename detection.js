// Import required modules
import vision from "https://cdn.jsdelivr.net/npm/@mediapipe/tasks-vision@0.10.0";
const { FaceLandmarker, FilesetResolver, DrawingUtils } = vision;

// Get DOM elements
const demosSection = document.getElementById("demos");
const videoBlendShapes = document.getElementById("video-blend-shapes");

// Initialize variables
let faceLandmarker;
let enableWebcamButton;
let runningMode = "IMAGE";
let webcamRunning = false;
const videoWidth = 480;
let opened = 0, closed = 0;

// Function to run the demo
async function runDemo() {
    // Initialize filesetResolver
    const filesetResolver = await FilesetResolver.forVisionTasks("https://cdn.jsdelivr.net/npm/@mediapipe/tasks-vision@0.10.0/wasm");

    // Create FaceLandmarker instance
    faceLandmarker = await FaceLandmarker.createFromOptions(filesetResolver, {
        baseOptions: {
            modelAssetPath: `https://storage.googleapis.com/mediapipe-models/face_landmarker/face_landmarker/float16/1/face_landmarker.task`,
            delegate: "GPU"
        },
        outputFaceBlendshapes: true,
        runningMode,
        numFaces: 1
    });

    // Show the demos section
    demosSection.classList.remove("invisible");
}

// Run the demo
runDemo();
// Get video and canvas elements
const video = document.getElementById("webcam");
const canvasElement = document.getElementById("output_canvas");
const canvasCtx = canvasElement.getContext("2d");

// Check if webcam access is supported
function hasGetUserMedia() {
    return !!(navigator.mediaDevices && navigator.mediaDevices.getUserMedia);
}

// Check if webcam is supported and add event listener to enable button
if (hasGetUserMedia()) {
    enableWebcamButton = document.getElementById("webcamButton");
    enableWebcamButton.addEventListener("click", enableCam);
} else {
    console.warn("getUserMedia() is not supported by your browser");
}

// Enable webcam and start detection
function enableCam(event) {
    if (!faceLandmarker) {
        console.log("Wait! faceLandmarker not loaded yet.");
        return;
    }

    // Toggle webcam running state
    if (webcamRunning === true) {
        webcamRunning = false;
        enableWebcamButton.innerText = "ENABLE PREDICTIONS";
    } else {
        webcamRunning = true;
        enableWebcamButton.innerText = "DISABLE PREDICTIONS";
    }

    // Define webcam stream constraints
    const constraints = {
        video: true
    };

    // Activate webcam stream
    navigator.mediaDevices.getUserMedia(constraints).then(function (stream) {
        video.srcObject = stream;
        video.addEventListener("loadeddata", predictWebcam);
    });
}

// Initialize variables for webcam prediction
let lastVideoTime = -1;
let results = undefined;
const drawingUtils = new DrawingUtils(canvasCtx);

// Function to perform face detection on webcam stream
async function predictWebcam() {
    const radio = video.videoHeight / video.videoWidth;
    video.style.width = videoWidth + "px";
    video.style.height = videoWidth * radio + "px";
    canvasElement.style.width = videoWidth + "px";
    canvasElement.style.height = videoWidth * radio + "px";
    canvasElement.width = video.videoWidth;
    canvasElement.height = video.videoHeight;

    // Change running mode to video
    if (runningMode === "IMAGE") {
        runningMode = "VIDEO";
        await faceLandmarker.setOptions({ runningMode: runningMode });
    }

    // Get current time
    let nowInMs = Date.now();

    // Detect faces in the video stream
    if (lastVideoTime !== video.currentTime) {
        lastVideoTime = video.currentTime;
        results = faceLandmarker.detectForVideo(video, nowInMs);
    }

    // Draw landmarks on the video frame
    if (results.faceLandmarks) {
        for (const landmarks of results.faceLandmarks) {
            drawingUtils.drawConnectors(landmarks, FaceLandmarker.FACE_LANDMARKS_TESSELATION, { color: "#C0C0C070", lineWidth: 1 });
            drawingUtils.drawConnectors(landmarks, FaceLandmarker.FACE_LANDMARKS_RIGHT_EYE, { color: "#FF3030" });
            drawingUtils.drawConnectors(landmarks, FaceLandmarker.FACE_LANDMARKS_RIGHT_EYEBROW, { color: "#FF3030" });
            drawingUtils.drawConnectors(landmarks, FaceLandmarker.FACE_LANDMARKS_LEFT_EYE, { color: "#30FF30" });
            drawingUtils.drawConnectors(landmarks, FaceLandmarker.FACE_LANDMARKS_LEFT_EYEBROW, { color: "#30FF30" });
            drawingUtils.drawConnectors(landmarks, FaceLandmarker.FACE_LANDMARKS_FACE_OVAL, { color: "#E0E0E0" });
            drawingUtils.drawConnectors(landmarks, FaceLandmarker.FACE_LANDMARKS_LIPS, { color: "#E0E0E0" });
            drawingUtils.drawConnectors(landmarks, FaceLandmarker.FACE_LANDMARKS_RIGHT_IRIS, { color: "#FF3030" });
            drawingUtils.drawConnectors(landmarks, FaceLandmarker.FACE_LANDMARKS_LEFT_IRIS, { color: "#30FF30" });
        }
    }
    // Calculate attentiveness score
    attentivness(results.faceBlendshapes);
    // Call the function again to keep predicting when the browser is ready
    if (webcamRunning === true) {
        window.requestAnimationFrame(predictWebcam);
    }
}

// Function to calculate attentiveness score
function attentivness(blendShapes) {
    let attentivnessScore = 0;
    if (!blendShapes.length) {
        return;
    }
    blendShapes[0].categories.map((shape) => {
        if ((shape.displayName || shape.categoryName) == "eyeBlinkLeft" || (shape.displayName || shape.categoryName) == "eyeBlinkRight") {
            if (shape.score > 0.3) {
                closed++;
                console.log("Closed: " + closed);
            } else {
                opened++;
                console.log("Opened: " + opened)
            }
        }
    });
    attentivnessScore = (opened / (opened + closed)) * 100;
    console.log("Attentivness Score: " + attentivnessScore + "%");
    document.getElementById("attentiveness").innerHTML = attentivnessScore + "%";
}
