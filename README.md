<p align="center">
  <img src="public/logo.png" width="120" alt="ORIPadi Logo">
</p>

<h1 align="center">ORIPadi</h1>

<p align="center">
  <strong>Advanced AI-Powered Diagnostic Tool for Malaysian Padi Farmers</strong>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel" alt="Laravel 12">
  <img src="https://img.shields.io/badge/Gemini-2.5--Flash-4285F4?style=for-the-badge&logo=google-gemini" alt="Gemini 2.5 Flash">
  <img src="https://img.shields.io/badge/Tailword-CSS-38B2AC?style=for-the-badge&logo=tailwind-css" alt="Tailwind CSS">
</p>

---

## 🌟 About ORIPadi

ORIPadi is a premium diagnostic web application designed to empower Malaysian rice farmers with instant, expert-level agricultural insights. By leveraging **Google's Gemini 2.5 Flash** computer vision technology, ORIPadi identifies padi leaf diseases from photos and provides localized, actionable intervention plans to protect crop yields and optimize resource usage.

## ✨ Key Features

- 📸 **Live AR Scan**: Interactive camera interface with real-time targeting for capturing high-quality leaf images.
- 🤖 **AI-Driven Diagnosis**: Precise identification of diseases with confidence percentages and visual reasoning.
- 🇲🇾 **Bilingual Experience**: Full localization in **Bahasa Melayu** and **English**, tailored for the local context.
- 🌾 **3-Step Intervention Plan**: Tailored advice covering:
    - **Irrigation (Pengairan)**: Water management strategies.
    - **Fertilization (Baja)**: Precise nutrient application.
    - **Treatment (Rawatan)**: Recommended pesticides or organic solutions.
- 🎙️ **Voice Context**: Support for voice/text descriptions in Bahasa Melayu to provide the AI with additional field observations.
- ♻️ **Resource Optimization**: AI-generated strategies to reduce wastage of water, fertilizer, and costs.
- 📄 **PDF Treatment Plans**: Exportable professional reports for offline use or sharing with agricultural officers.

## 🏗️ Architectural Overview

ORIPadi follows a modern decoupled architecture where the Laravel backend acts as a robust orchestrator for AI services and document generation.

### Tech Stack
- **Backend**: Laravel 12 (PHP 8.2+)
- **Frontend**: Tailwind CSS with Blade & Alpine.js-style interactions.
- **AI Engine**: Google Generative AI (Gemini 2.5 Flash API).
- **PDF Engine**: Barryvdh/Laravel-DomPDF.
- **Environment**: Dockerized via Laravel Sail for consistent development.

### Workflow
1. **Input**: Farmer captures a photo via the web interface and optionally adds voice/text context.
2. **Orchestration**: Laravel transmits the multi-modal data to the Gemini API with a specialized Malaysian agricultural prompt.
3. **Analysis**: Gemini performs visual reasoning and returns a structured JSON response.
4. **Output**: The application renders an interactive diagnosis dashboard and offers a downloadable PDF report.

---

## 🚀 Setup & Installation

### Prerequisites
- **PHP 8.2+**
- **Composer**
- **Node.js & NPM**
- **Google Gemini API Key** ([Get it here](https://aistudio.google.com/))

### 1. Clone the Repository
```bash
git clone https://github.com/your-username/ORIPadi.git
cd ORIPadi
```

### 2. Automated Setup
ORIPadi includes a custom setup script to handle dependencies, environment files, keys, and migrations in one go:
```bash
composer run setup
```

### 3. Configure API Keys
Open the `.env` file and add your Gemini API Key:
```env
GEMINI_API_KEY=your_api_key_here
GEMINI_MODEL=gemini-2.5-flash
```

### 4. Run the Application
Start the development server (runs PHP, Vite, and Queue listeners simultaneously):
```bash
composer run dev
```
Visit `http://localhost:8000` in your browser.

---

## 🛠️ Development Commands

The following shortcuts are available via `composer.json`:

- `composer run setup`: Full project initialization.
- `composer run dev`: Starts the server, Vite, and queue listeners concurrently.
- `composer run test`: Runs the PHPUnit test suite.
- `php artisan sail up`: Starts the Docker-based development environment.

## 📄 License

ORIPadi is open-sourced software licensed under the [MIT license](LICENSE).
