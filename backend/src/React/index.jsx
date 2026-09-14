import React from "react";
import ReactDOM from "react-dom/client"
//import BooksPage from "./Components/BooksPage.jsx";
//import AlbumsAdmin from "./AdminPages/Albums/Albums.jsx";
import ServicesPage from "./PublicPages/Services";
//import App from "./App";

const targetRoot = "root";
ReactDOM.createRoot(document.getElementById(targetRoot)).render(
    <React.StrictMode>
        <ServicesPage />
    </React.StrictMode>
)
