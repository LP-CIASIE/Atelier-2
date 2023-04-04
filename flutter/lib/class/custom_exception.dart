class CustomException implements Exception {
  final String message;
  // final String email;
  // final String role;

  CustomException({
    required this.message,
  });

  @override
  String toString() => message;
}
