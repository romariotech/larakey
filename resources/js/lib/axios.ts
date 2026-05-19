import axios from 'axios';
import type { AxiosError, AxiosResponse } from 'axios';

interface ApiErrorResponse {
    message?: string;
    errors?: Record<string, string[]>;
}

export interface CustomAxiosError extends AxiosError<ApiErrorResponse> {
    customMessage?: string;
}

const api = axios.create();

api.interceptors.response.use(
    (response: AxiosResponse) => {
        return response;
    },
    (error: CustomAxiosError) => {
        let errorMessage = 'An unexpected error occurred. Please try again.';

        if (error.response) {
            const data = error.response.data;

            if (data?.message) {
                errorMessage = data.message;
            } else if (data?.errors) {
                const firstErrorKey = Object.keys(data.errors)[0];

                if (firstErrorKey && data.errors[firstErrorKey].length > 0) {

                    errorMessage = data.errors[firstErrorKey][0];
                }
            }
        } else if (error.request) {
            errorMessage = 'Unable to connect to the server. Please check your connection.';
        }

        error.message = errorMessage;

        return Promise.reject(error);
    }
);

export default api;
