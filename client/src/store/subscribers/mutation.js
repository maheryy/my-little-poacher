import axios from "axios";

const mutationSubscriber = (mutation, state) => {
  switch (mutation.type) {
    case "auth/setToken":
      if (mutation.payload) {
        axios.defaults.headers.common[
          "Authorization"
        ] = `Bearer ${mutation.payload}`;
        localStorage.setItem("token", mutation.payload);
      } else {
        axios.defaults.headers.common["Authorization"] = null;
        localStorage.removeItem("token");
      }
      break;
  }
};

export default mutationSubscriber;
