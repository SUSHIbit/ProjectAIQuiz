export class FileUploadHandler {
    constructor() {
        this.initializeElements();
        this.bindEvents();
        this.uploadInProgress = false;
        this.allowedTypes = ["pdf", "doc", "docx", "ppt", "pptx"];
        this.maxFileSize = 15 * 1024 * 1024; // 15MB
    }

    initializeElements() {
        this.uploadArea = document.getElementById("upload-area");
        this.fileInput = document.getElementById("file-input");
        this.uploadContent = document.getElementById("upload-content");
        this.uploadSuccess = document.getElementById("upload-success");
        this.uploadLoading = document.getElementById("upload-loading");
        this.uploadProgress = document.getElementById("upload-progress");
        this.progressBar = document.getElementById("progress-bar");
        this.errorMessage = document.getElementById("error-message");
        this.continueSection = document.getElementById("continue-section");
        this.fileInfo = document.getElementById("file-info");
        this.removeFileBtn = document.getElementById("remove-file");
        this.generateQuizBtn = document.getElementById("generate-quiz-btn");
        this.generateFlashcardsBtn = document.getElementById(
            "generate-flashcards-btn"
        );
    }

    bindEvents() {
        if (!this.uploadArea) return;

        // Click to upload
        this.uploadArea.addEventListener("click", () => {
            if (!this.uploadInProgress) {
                this.fileInput.click();
            }
        });

        // Enhanced drag and drop events
        this.uploadArea.addEventListener("dragover", (e) =>
            this.handleDragOver(e)
        );
        this.uploadArea.addEventListener("dragleave", (e) =>
            this.handleDragLeave(e)
        );
        this.uploadArea.addEventListener("drop", (e) => this.handleDrop(e));

        // File input change with validation
        this.fileInput.addEventListener("change", (e) =>
            this.handleFileChange(e)
        );

        // Remove file with confirmation
        this.removeFileBtn?.addEventListener("click", () =>
            this.confirmRemoveFile()
        );

        // Generate buttons
        this.generateQuizBtn?.addEventListener("click", () =>
            this.navigateToQuizGenerator()
        );
        this.generateFlashcardsBtn?.addEventListener("click", () =>
            this.navigateToFlashcardGenerator()
        );

        // Prevent default drag/drop on document
        document.addEventListener("dragover", (e) => e.preventDefault());
        document.addEventListener("drop", (e) => e.preventDefault());
    }

    handleDragOver(e) {
        e.preventDefault();
        if (!this.uploadInProgress) {
            this.uploadArea.classList.add("border-slate-400", "bg-slate-50");
            this.uploadArea.style.transform = "scale(1.02)";
        }
    }

    handleDragLeave(e) {
        e.preventDefault();
        this.uploadArea.classList.remove("border-slate-400", "bg-slate-50");
        this.uploadArea.style.transform = "scale(1)";
    }

    handleDrop(e) {
        e.preventDefault();
        this.uploadArea.classList.remove("border-slate-400", "bg-slate-50");
        this.uploadArea.style.transform = "scale(1)";

        if (!this.uploadInProgress) {
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                this.validateAndUpload(files[0]);
            }
        }
    }

    handleFileChange(e) {
        if (e.target.files.length > 0 && !this.uploadInProgress) {
            this.validateAndUpload(e.target.files[0]);
        }
    }

    validateAndUpload(file) {
        // Validate file size
        if (file.size > this.maxFileSize) {
            this.showError(
                `File size must be less than ${
                    this.maxFileSize / 1024 / 1024
                }MB. Please choose a smaller file.`
            );
            this.fileInput.value = "";
            return;
        }

        // Validate file type
        const fileExtension = file.name.split(".").pop().toLowerCase();
        if (!this.allowedTypes.includes(fileExtension)) {
            this.showError(
                "Only PDF, DOC/DOCX, and PPT/PPTX files are allowed."
            );
            this.fileInput.value = "";
            return;
        }

        // Validate file name
        if (file.name.length > 255) {
            this.showError(
                "File name is too long. Please rename your file and try again."
            );
            this.fileInput.value = "";
            return;
        }

        this.uploadFile(file);
    }

    async uploadFile(file) {
        if (this.uploadInProgress) {
            this.showError("Upload already in progress. Please wait...");
            return;
        }

        this.uploadInProgress = true;
        this.hideError();
        this.showLoading();

        const formData = new FormData();
        formData.append("file", file);
        formData.append(
            "_token",
            document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content")
        );
        formData.append(
            "upload_id",
            Math.random().toString(36).substring(2, 15)
        );

        // Simulate realistic progress
        let progress = 0;
        const progressInterval = setInterval(() => {
            progress += Math.random() * 25;
            if (progress > 90) progress = 90;
            this.updateProgress(progress);
        }, 400);

        try {
            const response = await fetch("/upload-file", {
                method: "POST",
                body: formData,
            });

            const data = await response.json();

            clearInterval(progressInterval);
            this.updateProgress(100);

            setTimeout(() => {
                this.hideLoading();
                this.uploadInProgress = false;

                if (data.success) {
                    this.showSuccess(data.file_info);
                } else {
                    this.showError(data.message);
                    if (data.redirect) {
                        setTimeout(() => {
                            window.location.href = data.redirect;
                        }, 2000);
                    }
                }
            }, 600);
        } catch (error) {
            clearInterval(progressInterval);
            this.hideLoading();
            this.uploadInProgress = false;
            this.showError(
                "Network error. Please check your connection and try again."
            );
            console.error("Upload error:", error);
        }
    }

    confirmRemoveFile() {
        if (confirm("Are you sure you want to remove this file?")) {
            this.removeFile();
        }
    }

    async removeFile() {
        try {
            const response = await fetch("/remove-file", {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                    "Content-Type": "application/json",
                },
            });

            const data = await response.json();
            if (data.success) {
                this.resetUploadArea();
            } else {
                this.showError("Failed to remove file. Please try again.");
            }
        } catch (error) {
            console.error("Error removing file:", error);
            this.showError("Failed to remove file. Please try again.");
        }
    }

    navigateToQuizGenerator() {
        this.setButtonLoading(this.generateQuizBtn, "Loading...");
        window.location.href = "/quiz/generator";
    }

    navigateToFlashcardGenerator() {
        this.setButtonLoading(this.generateFlashcardsBtn, "Loading...");
        window.location.href = "/flashcards/ai/generator";
    }

    setButtonLoading(button, text) {
        if (button) {
            button.disabled = true;
            button.innerHTML = `
                <svg class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                ${text}
            `;
        }
    }

    showLoading() {
        this.uploadContent.classList.add("hidden");
        this.uploadSuccess.classList.add("hidden");
        this.uploadLoading.classList.remove("hidden");
        this.uploadProgress.classList.remove("hidden");
        this.updateProgress(0);
    }

    hideLoading() {
        this.uploadLoading.classList.add("hidden");
        this.uploadProgress.classList.add("hidden");
    }

    updateProgress(percent) {
        if (this.progressBar) {
            this.progressBar.style.width = `${Math.min(
                100,
                Math.max(0, percent)
            )}%`;
        }
    }

    showSuccess(fileData) {
        this.uploadContent.classList.add("hidden");
        this.uploadSuccess.classList.remove("hidden");
        this.continueSection.classList.remove("hidden");

        this.fileInfo.innerHTML = `
            <span class="font-medium text-slate-900">${fileData.name}</span>
            <span class="text-slate-500">(${fileData.size})</span>
            ${
                fileData.type
                    ? `<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-700 ml-2">${fileData.type}</span>`
                    : ""
            }
        `;
    }

    showError(message) {
        this.uploadContent.classList.remove("hidden");
        this.uploadSuccess.classList.add("hidden");
        this.continueSection.classList.add("hidden");

        this.errorMessage.classList.remove("hidden");
        this.errorMessage.querySelector("p").textContent = message;

        // Auto-hide error after 10 seconds
        setTimeout(() => {
            this.hideError();
        }, 10000);
    }

    hideError() {
        this.errorMessage.classList.add("hidden");
    }

    resetUploadArea() {
        this.uploadContent.classList.remove("hidden");
        this.uploadSuccess.classList.add("hidden");
        this.uploadLoading.classList.add("hidden");
        this.uploadProgress.classList.add("hidden");
        this.continueSection.classList.add("hidden");
        this.hideError();
        this.fileInput.value = "";
        this.uploadInProgress = false;
        this.uploadArea.style.transform = "scale(1)";
    }
}

// Initialize when DOM is loaded
document.addEventListener("DOMContentLoaded", () => {
    new FileUploadHandler();
});
