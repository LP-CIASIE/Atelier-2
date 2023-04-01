import 'package:flutter/material.dart';

class ProfilPage extends StatelessWidget {
  final Map<String, dynamic>? user;
  const ProfilPage({super.key, required this.user});

  // This widget is the root of your application.
  @override
  Widget build(BuildContext context) {
    final formKey = GlobalKey<FormState>();
    bool formPending = false;
    String errorMessage = '';
    final emailController = TextEditingController(text: user?['email']);
    final lastnameController = TextEditingController(text: user?['lastname']);
    final firstnameController = TextEditingController(text: user?['firstname']);
    final passwordController = TextEditingController();
    final passwordConfirmController = TextEditingController();
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
              key: formKey,
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.stretch,
                children: [
                  Padding(
                    padding: const EdgeInsets.symmetric(vertical: 12),
                    child: TextFormField(
                      controller: emailController,
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
                      controller: firstnameController,
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
                      controller: lastnameController,
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
                      controller: passwordController,
                      validator: (value) {
                        if (value.toString().length < 8) {
                          return 'Mot de passe trop court';
                        }
                        if (value == null || value.isEmpty) {
                          return 'Mot de passe non renseigné';
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
                      controller: passwordConfirmController,
                      validator: (value) {
                        if (value.toString() != passwordController.text) {
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
                        textAlign: TextAlign.start,
                        style: const TextStyle(color: Colors.red),
                      ),
                    ),
                  ),
                  Padding(
                    padding: const EdgeInsets.symmetric(vertical: 12),
                    child: Container(
                      alignment: Alignment.centerRight,
                      child: ElevatedButton(
                        onPressed: formPending ? null : null,
                        child: const Text("S'inscrire"),
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
