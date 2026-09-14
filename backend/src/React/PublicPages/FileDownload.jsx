import React from "react";

export function DownloadPage({fileUrl, fileName}){
    const downloadFile = () => {
        try{
            let a = document.createElement("a");
            a.href = fileUrl;
            a.click();
        } catch(err){
            console.log(err);
        }
    };
    return (
        <>
        <div className="flex margin-auto">
            <div className="file-name">
                <h1>{fileName}</h1>
            </div>
            <button type="button" className="btn btn-primary" onClick={downloadFile}>Download</button>
        </div>
        </>
    );
}