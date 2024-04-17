import { createSlice, createAsyncThunk } from "@reduxjs/toolkit";
import {getToken, fetchGet, fetchPost, fetchPatch, fetchDelete } from '../service/Httpservice.js';
import { loader } from '../service/UtilService.js';
import { sortArray } from "../service/UtilService.js";
import env from '../env.js';
import toastr from 'toastr'
toastr.options = env.toastrOptions;

export const fetchGetMovies = createAsyncThunk("movie/getMovies", async (payload) => {
    const token = await getToken();
    let query = `/api/movies?page=${payload.pageItem}`;
    let response = await fetchGet(query, token);

    return response;
});

export const fetchAddMovie = createAsyncThunk("movie/addMovie", async (payload) => {
    loader();
    const token = await getToken();
    let response = await fetchPost(`/api/movies`, payload.movie, token);
    loader(false);

    return response;
});

export const fetchUpdateMovie = createAsyncThunk("movie/updateMovie", async (payload) => {
    loader();
    const token = await getToken();
    let response = await fetchPatch(`/api/movies/${payload.movie.id}`, payload.movie, token);
    loader(false);

    return {
        movie: response,
        index: payload.index
    };
});

export const fetchDeleteMovie = createAsyncThunk("movie/deleteMovie", async (payload) => {
    loader();
    const token = await getToken();
    await fetchDelete(`/api/movies/${payload.id}`, token);
    loader(false);

    return {index: payload.index};
});

const {actions, reducer:movieReducer } = createSlice({
    name: "movie",
    initialState: {
        movies: [],
        totalItems: 0,
    },
    reducers: {
        sortMovies(state, action) {
            state.movies = sortArray(action.payload.movies, action.payload.field, action.payload.order, action.payload.type);
        }, 
        deleteMoviePhoto(state, action) {
            let payload = action.payload;
            state.movies[payload.movieIndex]['photos'].splice(payload.photoIndex, 1);
        }
    },
    extraReducers: (builder) => {
        builder
            .addCase(fetchGetMovies.pending, (state) => {
                loader();
            })
            .addCase(fetchGetMovies.fulfilled, (state, action) => {
                state.movies = action.payload['hydra:member'];
                state.totalItems = action.payload['hydra:totalItems'];
                loader(false);
                if (action.payload.status !== undefined && action.payload.status !== 200)toastr.error('Lister des films', 'Erreur');
            })
            .addCase(fetchGetMovies.rejected, (state, action) => {alert("ok")
                loader(false);
                toastr.error('Lister des films', 'Erreur');
            })
            .addCase(fetchAddMovie.fulfilled, (state, action) => {
                state.movies.unshift(action.payload);
                toastr.success('Ajouter un film', 'Enregistré');
            })
            .addCase(fetchUpdateMovie.fulfilled, (state, action) => {
                state.movies[action.payload.index] = action.payload.movie;
                toastr.success('Modifier un film', 'Enregistré');
            })
            .addCase(fetchDeleteMovie.fulfilled, (state, action) => {
                state.movies.splice(action.payload.index, 1);
                toastr.success('Supprimer un film', 'Enregistré');
            })
        ;
    }
});

export const { sortMovies, deleteMoviePhoto } = actions;
export default movieReducer;