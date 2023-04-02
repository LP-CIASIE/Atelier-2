import 'dart:convert';

import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;

class ProfilPage extends StatefulWidget {
  final Map<String, dynamic>? user;
  const ProfilPage({super.key, this.user});

  @override
  State<ProfilPage> createState() => _ProfilPageState();
}

class _ProfilPageState extends State<ProfilPage> {
  final _formKey = GlobalKey<FormState>();
  bool formPending = false;
  late final TextEditingController _emailController;
  late final TextEditingController _lastnameController;
  late final TextEditingController _firstnameController;
  late final TextEditingController _passwordController;
  late final TextEditingController _passwordConfirmController;

  @override
  void initState() {
    _emailController = TextEditingController(text: widget.user?['email']);
    _lastnameController = TextEditingController(text: widget.user?['lastname']);
    _firstnameController =
        TextEditingController(text: widget.user?['firstname']);
    _passwordController = TextEditingController();
    _passwordConfirmController = TextEditingController();
    super.initState();
  }

  Future<void> _submitForm(context) async {
    setState(() {
      formPending = true;
    });

    Map bodyHttp = {
      'email': _emailController.text,
      'lastname': _lastnameController.text,
      'firstname': _firstnameController.text
    };
    if (_passwordController.text != '') {
      bodyHttp['password'] = _passwordController.text;
    }

    try {
      dynamic responseHttp = await http.put(
        Uri.parse('http://gateway.atelier.local:8000/users'),
        headers: <String, String>{
          'Authorization': 'Bearer ${widget.user!['accessToken']}',
          'Content-Type': 'application/json; charset=UTF-8',
        },
        body: jsonEncode(bodyHttp),
      );

      if (!responseHttp.body.isEmpty) {
        Map<String, dynamic> response = jsonDecode(responseHttp.body);

        if (response.containsKey('code')) {
          setState(() {
            SnackBar(content: Text(utf8.decode(response['message'].codeUnits)));
          });
        }
        setState(() {
          formPending = false;
        });
      } else {
        setState(() {
          formPending = false;
        });
        const SnackBar(content: Text('Modifications enregistrées'));
      }
    } catch (error) {
      setState(() {
        const SnackBar(
            content: Text(
                'Un problème est survenu, veuillez vérifier votre connexion internet et réessayer'));
        formPending = false;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        // Here we take the value from the FruitMaster object that was created by
        // the App.build method, and use it to set our appbar title.
        title: const Text('Mon Profil'),
      ),
      body: Column(
        children: [
          Padding(
            padding: const EdgeInsets.all(12),
            child: Form(
              key: _formKey,
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.stretch,
                children: [
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
                        if (value!.isNotEmpty && value.toString().length < 8) {
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
                  Padding(
                    padding: const EdgeInsets.symmetric(vertical: 12),
                    child: Container(
                      alignment: Alignment.centerRight,
                      child: ElevatedButton(
                        onPressed: formPending
                            ? null
                            : () async {
                                if (_formKey.currentState!.validate()) {
                                  final BuildContext context = this.context;
                                  _submitForm(context);
                                }
                              },
                        child: const Text("Enregistrer les modifications"),
                      ),
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
