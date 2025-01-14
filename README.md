## Frontend

### Description
The frontend is a web-based user interface built with HTML, CSS, and JavaScript. It contains all the visual and interactive elements for user interaction.

### Features
- User authentication pages (e.g., reset password, login).
- Admin dashboard.
- Blog and content pages.
- Pre-configured assets for styling and scripting.

### Requirements
- A web server (e.g., Apache, Nginx) or local development server.
- Prepros (optional, for SCSS compilation).

### Installation and Setup
1. **Clone the repository or copy the files to your server.**
   ```bash
   git clone <repository-url>
   ```
2. **Ensure all dependencies are in place:**
   - Verify the presence of required assets (CSS, JS, fonts, etc.).
   - Check `konfigurasi.php` and `config` for any necessary updates.

3. **Start the frontend:**
   - Use a local server (e.g., `Live Server` in VS Code) or deploy the files to your hosting environment.

4. **Access the frontend:**
   Navigate to the main entry point (e.g., `index.html` or `dashboard/index.html`) in your browser.

---

## Project Structure

### Frontend
- `css/`: Stylesheets for the application.
- `js/`: JavaScript files for interactivity.
- `scss/`: Source SCSS files (compile using Prepros or similar tools).
- `uploads/`: Directory for user-uploaded files.
- `config/`: Configuration files.
- `*.html`: Static HTML files for various pages.

---

## Notes
- Ensure all sensitive data (e.g., database credentials) is secured and not exposed in version control.
- Test the application in a staging environment before deploying to production.


