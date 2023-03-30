import { defineStore } from "pinia";
import { inject, reactive } from "vue";

export const useSessionStore = defineStore(
  "session",
  () => {
    const API = inject("api");
    const user = reactive({
      id: "",
      email: "",
      access_token: "",
      refresh_token: "",
    });

    async function signIn(form) {
      return API.post("/signin", {
        email: form.email.content,
        password: form.password.content,
      })
        .then((response) => {
          user.access_token = response.data["access-token"];
          user.refresh_token = response.data["refresh-token"];
          return {
            ok: true,
          };
        })
        .catch((error) => {
          console.log(error);
          return {
            ok: false,
            message: "",
          };
        });
    }

    return { user, signIn };
  },
  {
    persist: true,
  }
);
