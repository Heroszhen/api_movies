import { createSlice, createAsyncThunk } from "@reduxjs/toolkit";
import {getToken, fetchGet, fetchPost, fetchPatch} from '../service/Httpservice.js';
import { loader } from '../service/UtilService.js';
import { sortArray } from "../service/UtilService.js";
import env from '../env.js';
import toastr from 'toastr'
toastr.options = env.toastrOptions;

export const fetchGetMovies = createAsyncThunk("movie/getMovies", async (payload) => {
    loader();
    const token = await getToken();
    let query = `/api/movies?page=${payload.pageItem}`;
    let response = await fetchGet(query, token);
    loader(false);

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

const {actions, reducer:movieReducer } = createSlice({
    name: "movie",
    initialState: {
        movies: [],
        totalItems: 0,
    },
    reducers: {
        sortMovies(state, action) {
            state.movies = sortArray(action.payload.movies, action.payload.field, action.payload.order, action.payload.type);
        }
    },
    extraReducers: (builder) => {
        builder
            .addCase(fetchGetMovies.fulfilled, (state, action) => {
                state.movies = action.payload['hydra:member'];
                state.totalItems = action.payload['hydra:totalItems'];
            })
            .addCase(fetchAddMovie.fulfilled, (state, action) => {
                state.movies.unshift(action.payload);
                toastr.success('Ajouter un film', 'Enregistré');
            })
            .addCase(fetchUpdateMovie.fulfilled, (state, action) => {
                state.movies[action.payload.index] = action.payload.movie;
                toastr.success('Modifier un film', 'Enregistré');
            })
        ;
    }
});

export const { sortMovies } = actions;
export default movieReducer;