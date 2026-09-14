;
function groupBooksByGenre(books) {
    let groups = new Map();
    books.forEach(book => {
        let name = book.genre.toLowerCase();
        if (!groups.get(name)) {
            groups.set(name, {
                name: name,
                books: []
            });
        }
        let group = groups.get(name);
        group?.books.push(book);
    });
    return Array.from(groups.values()).sort((a, b) => a.name < b.name ? -1 : 1);
}
export { groupBooksByGenre };
