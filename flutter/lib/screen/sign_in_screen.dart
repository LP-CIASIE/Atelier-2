import 'dart:convert';

import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'package:lp1_ciasie_atelier_2/provider/session_provider.dart';
import 'package:lp1_ciasie_atelier_2/screen/home_screen.dart';
import 'package:provider/provider.dart';

class SignInPage extends StatefulWidget {
  const SignInPage({super.key});

  @override
  State<SignInPage> createState() => _SignInPageState();
}

class _SignInPageState extends State<SignInPage> {
  final _formKey = GlobalKey<FormState>();
  bool formPending = false;
  String errorMessage = '';
  final _emailController = TextEditingController();
  String errorMessageMail = '';
  final _passwordController = TextEditingController();
  String errorMessagePassword = '';

  Future<void> _submitForm(context) async {
    setState(() {
      formPending = true;
      errorMessage = '';
    });
    Map<String, dynamic> connection =
        await Provider.of<SessionProvider>(context, listen: false)
            .signIn(_emailController.text, _passwordController.text);

    if (connection['ok']) {
      Navigator.push(
        context,
        MaterialPageRoute(builder: (context) => const HomePage()),
      );
    } else {
      setState(() {
        formPending = false;
        errorMessage = connection['message'];
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text("Se connecter")),
      body: Column(children: [
        Form(
            key: _formKey,
            child: Column(
              children: [
                Text(errorMessage),
                TextFormField(
                  controller: _emailController,
                  validator: (value) {
                    if (value == null || value.isEmpty) {
                      return 'Veuillez entrer votre adresse e-mail';
                    }
                    return null;
                  },
                  decoration: const InputDecoration(
                    border: OutlineInputBorder(),
                    labelText: 'Adresse e-mail',
                  ),
                ),
                TextFormField(
                  controller: _passwordController,
                  validator: (value) {
                    if (value == null || value.isEmpty) {
                      return 'Veuillez entrer votre mot de passe';
                    }
                    return null;
                  },
                  decoration: const InputDecoration(
                    border: OutlineInputBorder(),
                    labelText: 'Mot de passe',
                  ),
                ),
                ElevatedButton(
                  onPressed: () async {
                    if (_formKey.currentState!.validate()) {
                      final BuildContext context = this.context;
                      _submitForm(context);
                    }
                  },
                  child: const Text("Se connecter"),
                ),
              ],
            )),
        // TextButton(
        //   onPressed: () {
        //     Navigator.push(
        //       context,
        //       MaterialPageRoute(builder: (context) => const SignUpScreen()),
        //     );
        //   },
        //   child: const Text("S'inscrire"),
        // ),
        Consumer<SessionProvider>(builder: (context, model, child) {
          return TextButton(
            onPressed: () async {
              setState(() {
                errorMessage = "Opération en cours de traitement...";
              });
              final body = {
                'refresh_token': model.user.refreshToken,
              };
              final response = await http.post(
                Uri.parse('https://fruits.shrp.dev/auth/logout'),
                headers: <String, String>{
                  'Content-Type': 'application/json; charset=UTF-8',
                },
                body: jsonEncode(body),
              );
              if (response.statusCode == 204) {
                setState(() {
                  errorMessage = "Déconnexion réussie";
                });
              } else {
                setState(() {
                  errorMessage =
                      "Un problème est survenu lors de la déconnexion";
                });
                // Erreur lors de l'envoi des données
              }
            },
            child: const Text(
              "Se déconnecter",
            ),
          );
        }),
      ]),
    );
  }
}
