export default {
    methods: {
        async fetchData(url, retries, method, data = {}) {
            const fetchAttempt = async (retryCount) => {
                try {
                    let response;
                    if (method === "post") {
                        response = await axios.post(url, data, {
                            withCredentials: false,
                        });
                    } else if (method === "get") {
                        response = await axios.get(url, {
                            withCredentials: false,
                        });

                    } else if (method === "put") {
                        response = await axios.put(url, data, {
                            withCredentials: false,
                        });

                    } else if (method === "delete") {
                        response = await axios.delete(url, data, {
                            withCredentials: false,
                        });
                    }
                    return response;
                } catch (error) {
                    if (retryCount <= 0) {
                        throw error;
                    } else {
                        await new Promise((resolve) => setTimeout(resolve, 2000));
                        return fetchAttempt(retryCount - 1);
                    }
                }
            };

            try {
                return await fetchAttempt(retries);
            } catch (error) {
                throw error; // Rethrow the error if all retry attempts fail
            }
        },
    },
};
