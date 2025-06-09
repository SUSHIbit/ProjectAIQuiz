export class FileUploadHandler {
    constructor() {
        this.initializeElements();
        this.bindEvents();
    }

    initializeElements() {
        this.uploadArea = document.getElementById("upload-area");
        this.fileInput = document.getElementById("file-input");
        this.uploadContent = document.getElementById("upload-content");
        this.uploadSuccess = document.getElementById("upload-success");
        this.uploadLoading = document.getElementById("upload-loading");
        this.errorMessage = document.getElementById("error-message");
        this.continueSection = document.getElementById("continue-section");
        this.fileInfo = document.getElementById("file-info");
        this.removeFileBtn = document.getElementById("remove-file");
        this.continueBtn = document.getElementById("continue-btn");
    }

    bindEvents() {
        if (!this.uploadArea) return;

        // Click to upload
        this.uploadArea.addEventListener("click", () => this.fileInput.click());

        // Drag and drop events
        this.uploadArea.addEventListener("dragover", (e) =>
            this.handleDragOver(e)
        );
        this.uploadArea.addEventListener("dragleave", (e) =>
            this.handleDragLeave(e)
        );
        this.uploadArea.addEventListener("drop", (e) => this.handleDrop(e));

        // File input change
        this.fileInput.addEventListener("change", (e) =>
            this.handleFileChange(e)
        );

        // Remove file
        this.removeFileBtn?.addEventListener("click", () => this.removeFile());

        // Continue button
        this.continueBtn?.addEventListener("click", () => this.continue());
    }

    handleDragOver(e) {
        e.preventDefault();
        this.uploadArea.classList.add("border-blue-400", "bg-blue-50");
    }

    handleDragLeave(e) {
        e.preventDefault();
        this.uploadArea.classList.remove("border-blue-400", "bg-blue-50");
    }

    handleDrop(e) {
        e.preventDefault();
        this.uploadArea.classList.remove("border-blue-400", "bg-blue-50");

        const files = e.dataTransfer.files;
        if (files.length > 0) {
            this.uploadFile(files[0]);
        }
    }

    handleFileChange(e) {
        if (e.target.files.length > 0) {
            this.uploadFile(e.target.files[0]);
        }
    }

    async uploadFile(file) {
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

        try {
            const response = await fetch("/upload-file", {
                method: "POST",
                body: formData,
            });

            const data = await response.json();
            this.hideLoading();

            if (data.success) {
                this.showSuccess(data.file_info);
                this.continueBtn.dataset.redirect = data.redirect;
            } else {
                this.showError(data.message);
                if (data.redirect) {
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 2000);
                }
            }
        } catch (error) {
            this.hideLoading();
            this.showError("An error occurred while uploading the file.");
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
            }
        } catch (error) {
            console.error("Error removing file:", error);
        }
    }

    continue() {
        const redirectUrl = this.continueBtn.dataset.redirect;
        if (redirectUrl) {
            window.location.href = redirectUrl;
        }
    }

    showLoading() {
        this.uploadContent.classList.add("hidden");
        this.uploadSuccess.classList.add("hidden");
        this.uploadLoading.classList.remove("hidden");
    }

    hideLoading() {
        this.uploadLoading.classList.add("hidden");
    }

    showSuccess(fileData) {
        this.uploadContent.classList.add("hidden");
        this.uploadSuccess.classList.remove("hidden");
        this.continueSection.classList.remove("hidden");
        this.fileInfo.textContent = `${fileData.name} (${fileData.size})`;
    }

    showError(message) {
        this.uploadContent.classList.remove("hidden");
        this.uploadSuccess.classList.add("hidden");
        this.continueSection.classList.add("hidden");
        this.errorMessage.classList.remove("hidden");
        this.errorMessage.querySelector("p").textContent = message;
    }

    hideError() {
        this.errorMessage.classList.add("hidden");
    }

    resetUploadArea() {
        this.uploadContent.classList.remove("hidden");
        this.uploadSuccess.classList.add("hidden");
        this.uploadLoading.classList.add("hidden");
        this.continueSection.classList.add("hidden");
        this.hideError();
        this.fileInput.value = "";
    }
}

// Initialize when DOM is loaded
document.addEventListener("DOMContentLoaded", () => {
    new FileUploadHandler();
});
