

interface Book {
    id: string;
    title: string;
    author: string;
    release_date: string;
    isbn: string;
    pdfUrl: string;
    owner: string;
    cover_image: number;
    linked_images: string[];
    description: string;
    likes: number;
    shares: number;
    views: number;
    genre: string;
    image_location: string;
}

export {Book};