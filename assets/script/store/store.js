import { configureStore } from "@reduxjs/toolkit";
import movieReducer from "./movie.slice.js";

export const store = configureStore({
    reducer: {
        movieReducer: movieReducer,
    }
});