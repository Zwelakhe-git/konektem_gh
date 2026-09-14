import React from 'react';
import DOMPurify from 'dompurify';

export default function SafeHtmlRenderer({ htmlContent }){
    const cleanHtml = DOMPurify.sanitize(htmlContent);
    return <div dangerouslySetInnerHTML={{ __html: cleanHtml }} />;
};