export const toDateDisplayFormat = (date) => {
  const dateObj = new Date(date);
  return dateObj.toLocaleDateString("en-US", {
    year: "numeric",
    month: "long",
    day: "numeric",
  });
};

export const getErrorMessagesFromResponse = (error) => {
  return error.response.data.violations.reduce(
    (acc, violation) => ({
      ...acc,
      [violation.propertyPath]: violation.message,
    }),
    {}
  );
};
