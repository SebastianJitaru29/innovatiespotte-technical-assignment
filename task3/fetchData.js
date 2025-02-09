const fs = require('fs');

/**
 * Fetch JSON data from a list of API URLs and combine the results.
 * If an API fails (HTTP error or timeout), logs the error and continues.
 *
 * @param {string[]} apiUrls - An array of API URLs to fetch data from.
 * @returns {Promise<Array>} A promise that resolves to a combined array of JSON data.
 */

async function fetchJSONFromAPIs(apiUrls) {

  // Map each URL to a fetch call wrapped with its own timeout and error handling.
  const results = await Promise.allSettled(
    apiUrls.map(async (url) => {

      const controller = new AbortController(); // Create an AbortController for this request. (From documentation)

      const timeoutId = setTimeout(() => controller.abort(), 5000);

      try {
        const response = await fetch(url, { signal: controller.signal });
        clearTimeout(timeoutId); 
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        return await response.json();
      } catch (error) {
        console.error(`Error fetching ${url}: ${error.message}`);
        return null; // Return null for failed requests. Not error as other links may work
      }
    })
  );

  const combinedData = [];
  for (const result of results) {
    if (result.status === "fulfilled" && result.value !== null) {
      Array.isArray(result.value)        // If the fetched JSON is an array, merge its items; otherwise, push the object.
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
