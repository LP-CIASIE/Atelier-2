import 'dart:convert';

import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'package:lp1_ciasie_atelier_2/provider/session_provider.dart';
import 'package:lp1_ciasie_atelier_2/screen/home_screen.dart';
import 'package:lp1_ciasie_atelier_2/screen/auth/sign_in_screen.dart';
import 'package:provider/provider.dart';
import 'package:flutter_dotenv/flutter_dotenv.dart';

class SignUpPage extends StatefulWidget {
  const SignUpPage({super.key});

  @override
  State<SignUpPage> createState() => _SignUpPageState();
}

class _SignUpPageState extends State<SignUpPage> {
  final _formKey = GlobalKey<FormState>();
  bool formPending = false;
  String errorMessage = '';
  final _emailController = TextEditingController();
  final _lastnameController = TextEditingController();
  final _firstnameController = TextEditingController();
  final _passwordController = TextEditingController();
  final _passwordConfirmController = TextEditingController();

  Future<void> _submitForm(context) async {
    setState(() {
      formPending = true;
      errorMessage = '';
    });

    Map bodyHttp = {
      'email': _emailController.text,
      'lastname': _lastnameController.text,
      'firstname': _firstnameController.text,
      'password': _passwordController.text,
      'role': 'user'
    };
    try {
      dynamic responseHttp = await http.post(
        Uri.parse('${dotenv.env['API_URL']}/signup'),
        headers: <String, String>{
          'Content-Type': 'application/json; charset=UTF-8',
        },
        body: jsonEncode(bodyHttp),
      );

      if (!responseHttp.body.isEmpty) {
        Map<String, dynamic> response = jsonDecode(responseHttp.body);

        if (response.containsKey('code')) {
          setState(() {
            errorMessage = utf8.decode(response['message'].codeUnits);
            formPending = false;
          });
        } else if (response.containsKey('user')) {
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
        } else {
          setState(() {
            errorMessage =
                'Un problème est survenu, veuillez vérifier votre connexion internet et réessayer.';
            formPending = false;
          });
        }
      } else {
        setState(() {
          errorMessage =
              'Un problème est survenu, veuillez vérifier votre connexion internet et réessayer.';
          formPending = false;
        });
      }
    } catch (error) {
      setState(() {
        errorMessage =
            'Un problème est survenu, veuillez vérifier votre connexion internet et réessayer.';
        formPending = false;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Padding(
            padding: const EdgeInsets.all(12),
            child: Form(
              key: _formKey,
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.stretch,
                children: [
                  const Padding(
                    padding: EdgeInsets.symmetric(vertical: 12),
                    child: Text(
                      'Inscription',
                      style: TextStyle(fontSize: 19.6),
                    ),
                  ),
                  Padding(
                    padding: const EdgeInsets.symmetric(vertical: 12),
                    child: TextFormField(
                      controller: _emailController,
                      validator: (value) {
                        if (value == null || value.isEmpty) {
                          return 'Email non renseignée';
                        }
                        final regex = RegExp(
                            r"^[a-zA-Z0-9.a-zA-Z0-9.!#$%&'*+-/=?^_`{|}~]+@[a-zA-Z0-9]+\.[a-zA-Z]+");
                        if (!regex.hasMatch(value)) {
                          return 'Email invalide';
                        }
                        return null;
                      },
                      decoration: const InputDecoration(
                        border: OutlineInputBorder(),
                        labelText: 'Email',
                      ),
                    ),
                  ),
                  Padding(
                    padding: const EdgeInsets.symmetric(vertical: 12),
                    child: TextFormField(
                      controller: _firstnameController,
                      validator: (value) {
                        if (value.toString().length < 2) {
                          return 'Prénom trop court';
                        }
                        if (value == null || value.isEmpty) {
                          return 'Prénom non renseigné';
                        }
                        return null;
                      },
                      decoration: const InputDecoration(
                        border: OutlineInputBorder(),
                        labelText: 'Prénom',
                      ),
                    ),
                  ),
                  Padding(
                    padding: const EdgeInsets.symmetric(vertical: 12),
                    child: TextFormField(
                      controller: _lastnameController,
                      decoration: const InputDecoration(
                        border: OutlineInputBorder(),
                        labelText: 'Nom',
                      ),
                    ),
                  ),
                  Padding(
                    padding: const EdgeInsets.symmetric(vertical: 12),
                    child: TextFormField(
                      obscureText: true,
                      controller: _passwordController,
                      validator: (value) {
                        if (value == null || value.isEmpty) {
                          return 'Mot de passe non renseigné';
                        }
                        if (value.toString().length < 8) {
                          return 'Mot de passe trop court';
                        }
                        return null;
                      },
                      decoration: const InputDecoration(
                        border: OutlineInputBorder(),
                        labelText: 'Mot de passe',
                      ),
                    ),
                  ),
                  Padding(
                    padding: const EdgeInsets.symmetric(vertical: 12),
                    child: TextFormField(
                      obscureText: true,
                      controller: _passwordConfirmController,
                      validator: (value) {
                        if (value.toString() != _passwordController.text) {
                          return 'Mots de passe non identiques';
                        }
                        return null;
                      },
                      decoration: const InputDecoration(
                        border: OutlineInputBorder(),
                        labelText: 'Confirmez votre mot de passe',
                      ),
                    ),
                  ),
                  Visibility(
                    visible: errorMessage != '',
                    child: Padding(
                      padding: const EdgeInsets.symmetric(vertical: 12),
                      child: Text(
                        errorMessage,
                        style: const TextStyle(color: Colors.red),
                      ),
                    ),
                  ),
                  Padding(
                    padding: const EdgeInsets.symmetric(vertical: 12),
                    child: Row(
                      mainAxisAlignment: MainAxisAlignment.end,
                      children: [
                        Padding(
                          padding: const EdgeInsets.only(right: 12),
                          child: OutlinedButton(
                            onPressed: formPending
                                ? null
                                : () {
                                    Navigator.push(
                                      context,
                                      MaterialPageRoute(
                                          builder: (context) =>
                                              const SignInPage()),
                                    );
                                  },
                            child: const Text("Se connecter"),
                          ),
                        ),
                        ElevatedButton(
                          onPressed: formPending
                              ? null
                              : () async {
                                  if (_formKey.currentState!.validate()) {
                                    final BuildContext context = this.context;
                                    _submitForm(context);
                                  }
                                },
                          child: const Text("S'inscrire"),
                        ),
                      ],
                    ),
                  ),
                ],
              ),
            ),
          )
        ],
      ),
    );
  }
}
