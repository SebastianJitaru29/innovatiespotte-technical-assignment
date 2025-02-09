const fs = require('fs');
const axios = require('axios');

/**
 * Fetch JSON data from a list of API URLs and combine the results.
 * If an API fails (HTTP error or timeout), logs the error and continues.
 *
 * @param {string[]} apiUrls - An array of API URLs to fetch data from.
 * @returns {Promise<Array>} A promise that resolves to a combined array of JSON data.
 */
async function fetchJSONFromAPIs(apiUrls) {
  // Map each URL to an axios call with its own timeout and error handling.

  const results = await Promise.allSettled( //Using allSettled to wait for all promises (Explained in README why I used allSettled)
    apiUrls.map(async (url) => {
      try {
        const response = await axios.get(url, { timeout: 5000 }); //Using axios to fetch data (Explained in README why I used axios)
        return response.data; 
      } catch (error) {
        console.error(`Error fetching ${url}: ${error.message}`);
        return null; // Return null for failed requests
      }
    })
  );

  // Combine all successful (non-null) results into a single array.
  const combinedData = [];
  for (const result of results) {
    if (result.status === "fulfilled" && result.value !== null) {
      Array.isArray(result.value)
        ? combinedData.push(...result.value)
        : combinedData.push(result.value);
    }
  }

  return combinedData;
}

(async () => {
  const apiUrls = [
    "https://jsonplaceholder.typicode.com/posts",
    "https://jsonplaceholder.typicode.com/comments",
    "https://jsonplaceholder.typicode.com/users",
  ];

  try {
    const data = await fetchJSONFromAPIs(apiUrls);
    console.log("Combined JSON Data:", data);

    fs.writeFileSync('combinedData.json', JSON.stringify(data, null, 2), 'utf-8');
    console.log('Combined data saved to combinedData.json');
  } catch (err) {
    console.error("An error occurred:", err);
  }
})();
