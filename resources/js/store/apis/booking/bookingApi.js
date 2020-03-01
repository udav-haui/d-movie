export function getFilm(filmId) {
    return axios.get(route('films.api.getFilm', {film: filmId}))
        .then(res => res)
        .catch(err => {
            console.error(err);
        });
}
