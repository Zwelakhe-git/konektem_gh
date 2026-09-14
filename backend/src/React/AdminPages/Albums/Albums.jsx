import React from "react";
import AlbumCreateForm from "./AlbumCreateForm";
import "./css/style.css"

export default function AlbumsAdmin(){
    return (
        <>
            <h2>Créer un nouvel album</h2>
            <AlbumCreateForm />
        </>
    );
}