import 'package:flutter/material.dart';

class ExceptionPage extends StatelessWidget {
  final Object? error;
  const ExceptionPage({
    super.key,
    this.error,
  });

  // This widget is the root of your application.
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
            padding: const EdgeInsets.symmetric(vertical: 12),
            child: Text(
              error.toString(),
              style: const TextStyle(fontSize: 19.6),
            ),
          ),
        ],
      ),
    );
  }
}
