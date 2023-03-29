import { defineStore } from "pinia";
import { inject, reactive } from "vue";
import { useRouter } from "vue-router";

export const useSessionStore = defineStore(
  "session",
  () => {
    const API = inject("api");
    const router = useRouter();
    const user = reactive({
      id: "",
      email: "",
      access_token: "",
      refresh_token: "",
    });

    function signIn(form) {
      API.post("/signin", {
        email: form.email,
        password: form.password,
      })
        .then((response) => {
          console.log(response);
        })
        .catch((error) => {
          console.log(error);
        });
    }

    function emptySession() {
      data.token = "";
      router.push("/login");
    }

    return { user, signIn };
  },
  {
    persist: true,
  }
);
